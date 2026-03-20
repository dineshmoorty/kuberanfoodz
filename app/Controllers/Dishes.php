<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;
use App\Models\Day;
use App\Models\Dish;
use App\Models\DishDay;

class Dishes extends BaseController
{
    protected function requireAdmin()
    {
        return $this->requireAdminAccess('Only admin can manage dishes');
    }

    protected function dishReferenceData(): array
    {
        return [
            'days' => (new Day())->orderBy('id', 'ASC')->findAll(),
            'categories' => (new Category())->orderBy('id', 'ASC')->findAll(),
        ];
    }

    protected function setDishModalFlash(string $mode, ?int $dishId = null): void
    {
        session()->setFlashdata('dish_modal_mode', $mode);

        if ($dishId !== null) {
            session()->setFlashdata('dish_modal_id', $dishId);
        }
    }

    protected function dishValidationRules(bool $isDaily, bool $isUpdate = false): array
    {
        $imageRule = $isUpdate
            ? 'permit_empty|is_image[dish_image]|max_size[dish_image,2048]|mime_in[dish_image,image/jpg,image/jpeg,image/png,image/webp]'
            : 'uploaded[dish_image]|is_image[dish_image]|max_size[dish_image,2048]|mime_in[dish_image,image/jpg,image/jpeg,image/png,image/webp]';

        $thumbnailRule = $isUpdate
            ? 'permit_empty|is_image[dish_thumbnails]|max_size[dish_thumbnails,2048]|mime_in[dish_thumbnails,image/jpg,image/jpeg,image/png,image/webp]'
            : 'uploaded[dish_thumbnails]|is_image[dish_thumbnails]|max_size[dish_thumbnails,2048]|mime_in[dish_thumbnails,image/jpg,image/jpeg,image/png,image/webp]';

        return [
            'dish_name' => 'required|min_length[2]|max_length[150]',
            'dish_price' => 'required|decimal',
            'dish_mrp' => 'required|decimal',
            'dish_desc' => 'permit_empty|max_length[5000]',
            'category_id' => 'required|integer|greater_than[0]',
            'status' => 'required|in_list[0,1]',
            'dish_image' => $imageRule,
            'dish_thumbnails' => $thumbnailRule,
        ];
    }

    protected function selectedDayIds(): array
    {
        $postedDayIds = $this->request->getPost('day_ids');
        if (!is_array($postedDayIds)) {
            return [];
        }

        $dayIds = [];

        foreach ($postedDayIds as $dayId) {
            $dayId = (int) $dayId;

            if ($dayId > 0) {
                $dayIds[$dayId] = $dayId;
            }
        }

        return array_values($dayIds);
    }

    protected function validateSelectedDays(Day $dayModel, array $dayIds, bool $isDaily)
    {
        if ($isDaily) {
            return null;
        }

        if (empty($dayIds)) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('day_ids', 'Please select at least one day.'));
        }

        $existingDayIds = array_map(
            static fn(array $day): int => (int) $day['id'],
            $dayModel->select('id')->whereIn('id', $dayIds)->findAll()
        );

        sort($dayIds);
        sort($existingDayIds);

        if ($dayIds !== $existingDayIds) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('day_ids', 'Please select valid day options.'));
        }

        return null;
    }

    protected function buildDishPayload(Category $categoryModel): ?array
    {
        $categoryId = (int) $this->request->getPost('category_id');
        $isDaily = $this->request->getPost('is_daily') ? 1 : 0;

        $category = $categoryModel->find($categoryId);
        if (!$category) {
            return null;
        }

        return [
            'dish_name' => trim((string) $this->request->getPost('dish_name')),
            'dish_price' => number_format((float) $this->request->getPost('dish_price'), 2, '.', ''),
            'dish_mrp' => number_format((float) $this->request->getPost('dish_mrp'), 2, '.', ''),
            'dish_desc' => trim((string) $this->request->getPost('dish_desc')) ?: null,
            'day_id' => null,
            'category_id' => $categoryId,
            'is_daily' => $isDaily,
            'status' => (int) $this->request->getPost('status'),
        ];
    }

    protected function validateUniqueDishName(Dish $model, string $dishName, ?int $ignoreId = null)
    {
        $query = $model->where('dish_name', $dishName);

        if ($ignoreId !== null) {
            $query->where('id !=', $ignoreId);
        }

        if ($query->first()) {
            return redirect()->back()->withInput()->with('validation', $this->validator->setError('dish_name', 'Dish name already exists.'));
        }

        return null;
    }

    protected function ensureUploadDirectory(string $relativePath): string
    {
        $fullPath = FCPATH . trim($relativePath, '/\\');

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        return $fullPath;
    }

    protected function uploadDishFile(string $field, string $directory, ?string $existingPath = null): ?string
    {
        $file = $this->request->getFile($field);

        if (!$file || !$file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return $existingPath;
        }

        $uploadPath = $this->ensureUploadDirectory($directory);
        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        return '/' . trim($directory, '/\\') . '/' . $newName;
    }

    protected function removeUploadedFile(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        $relativePath = ltrim($relativePath, '/\\');
        $fullPath = FCPATH . $relativePath;

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    protected function syncDishDays(DishDay $dishDayModel, int $dishId, array $dayIds): void
    {
        $dishDayModel->where('dish_id', $dishId)->delete();

        if (empty($dayIds)) {
            return;
        }

        $insertRows = [];

        foreach ($dayIds as $dayId) {
            $insertRows[] = [
                'dish_id' => $dishId,
                'day_id' => $dayId,
            ];
        }

        $dishDayModel->insertBatch($insertRows);
    }

    protected function loadDishDayMap(array $dishes): array
    {
        $dishDayMap = [];
        $dishIds = array_values(array_filter(array_map(static fn(array $dish): int => (int) ($dish['id'] ?? 0), $dishes)));

        if (!empty($dishIds)) {
            $rows = (new DishDay())
                ->select('dish_id, day_id')
                ->whereIn('dish_id', $dishIds)
                ->orderBy('id', 'ASC')
                ->findAll();

            foreach ($rows as $row) {
                $dishId = (int) $row['dish_id'];
                $dayId = (int) $row['day_id'];
                $dishDayMap[$dishId][] = $dayId;
            }
        }

        foreach ($dishes as $dish) {
            $dishId = (int) ($dish['id'] ?? 0);

            if (!isset($dishDayMap[$dishId])) {
                $dishDayMap[$dishId] = [];
            }

            if (!empty($dish['day_id'])) {
                $legacyDayId = (int) $dish['day_id'];

                if (!in_array($legacyDayId, $dishDayMap[$dishId], true)) {
                    $dishDayMap[$dishId][] = $legacyDayId;
                }
            }
        }

        return $dishDayMap;
    }

    public function list()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $model = new Dish();
        $dishes = $model->orderBy('id', 'DESC')->paginate(10);
        $modalDishId = (int) (session()->getFlashdata('dish_modal_id') ?: 0);

        return view('/admin/dishes/list', array_merge([
            'dishes' => $dishes,
            'dishDayMap' => $this->loadDishDayMap($dishes),
            'pager' => $model->pager,
            'modalMode' => session()->getFlashdata('dish_modal_mode') ?: '',
            'modalDishId' => $modalDishId,
        ], $this->dishReferenceData()));
    }

    public function create()
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $dishModel = new Dish();
        $dishDayModel = new DishDay();
        $categoryModel = new Category();
        $dayModel = new Day();
        $isDaily = $this->request->getPost('is_daily') ? true : false;
        $dayIds = $this->selectedDayIds();

        if (!$this->validate($this->dishValidationRules($isDaily))) {
            $this->setDishModalFlash('create');

            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        if ($redirect = $this->validateSelectedDays($dayModel, $dayIds, $isDaily)) {
            $this->setDishModalFlash('create');

            return $redirect;
        }

        $data = $this->buildDishPayload($categoryModel);
        if (!$data) {
            $this->setDishModalFlash('create');

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('category_id', 'Please select a valid category.'));
        }

        if ($redirect = $this->validateUniqueDishName($dishModel, $data['dish_name'])) {
            $this->setDishModalFlash('create');

            return $redirect;
        }

        $data['dish_image'] = $this->uploadDishFile('dish_image', 'uploads/dish-image');
        $data['dish_thumbnails'] = $this->uploadDishFile('dish_thumbnails', 'uploads/dish_thumbnails');

        $dishId = $dishModel->insert($data, true);
        $this->syncDishDays($dishDayModel, (int) $dishId, $isDaily ? [] : $dayIds);

        return redirect()->to('/admin/dishes')->with('success', 'Dish created successfully');
    }

    public function update($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $dishModel = new Dish();
        $dishDayModel = new DishDay();
        $categoryModel = new Category();
        $dayModel = new Day();
        $dish = $dishModel->find($id);

        if (!$dish) {
            return redirect()->to('/admin/dishes')->with('error', 'Dish not found');
        }

        $isDaily = $this->request->getPost('is_daily') ? true : false;
        $dayIds = $this->selectedDayIds();

        if (!$this->validate($this->dishValidationRules($isDaily, true))) {
            $this->setDishModalFlash('edit', (int) $id);

            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        if ($redirect = $this->validateSelectedDays($dayModel, $dayIds, $isDaily)) {
            $this->setDishModalFlash('edit', (int) $id);

            return $redirect;
        }

        $data = $this->buildDishPayload($categoryModel);
        if (!$data) {
            $this->setDishModalFlash('edit', (int) $id);

            return redirect()->back()->withInput()->with('validation', $this->validator->setError('category_id', 'Please select a valid category.'));
        }

        if ($redirect = $this->validateUniqueDishName($dishModel, $data['dish_name'], (int) $id)) {
            $this->setDishModalFlash('edit', (int) $id);

            return $redirect;
        }

        $previousDishImage = $dish['dish_image'] ?? null;
        $previousThumbnail = $dish['dish_thumbnails'] ?? null;

        $data['dish_image'] = $this->uploadDishFile('dish_image', 'uploads/dish-image', $previousDishImage);
        $data['dish_thumbnails'] = $this->uploadDishFile('dish_thumbnails', 'uploads/dish_thumbnails', $previousThumbnail);

        if ($data['dish_image'] !== $previousDishImage) {
            $this->removeUploadedFile($previousDishImage);
        }

        if ($data['dish_thumbnails'] !== $previousThumbnail) {
            $this->removeUploadedFile($previousThumbnail);
        }

        $dishModel->update($id, $data);
        $this->syncDishDays($dishDayModel, (int) $id, $isDaily ? [] : $dayIds);

        return redirect()->to('/admin/dishes')->with('success', 'Dish updated successfully');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireAdmin()) {
            return $redirect;
        }

        $dishModel = new Dish();
        $dish = $dishModel->find($id);

        if (!$dish) {
            return redirect()->to('/admin/dishes')->with('error', 'Dish not found');
        }

        $this->removeUploadedFile($dish['dish_image'] ?? null);
        $this->removeUploadedFile($dish['dish_thumbnails'] ?? null);

        $dishModel->delete($id);

        return redirect()->to('/admin/dishes')->with('success', 'Dish deleted successfully');
    }
}
