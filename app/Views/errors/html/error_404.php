<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Errors.pageNotFound') ?></title>

    <style>
        body {
            margin: 0;
            height: 100%;
            background: radial-gradient(circle at top, #ffebe6 0%, #f7f0ff 60%, #f8f9fb 100%);
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #3b3b3b;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .error-card {
            max-width: 540px;
            width: 100%;
            padding: 2.5rem 2rem;
            background: rgba(255, 255, 255, 0.92);
            border-radius: 1rem;
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .error-card h1 {
            font-size: 3.25rem;
            margin: 0 0 0.5rem;
            color: #dd4814;
        }

        .error-card h2 {
            margin-top: 0;
            font-size: 1.2rem;
            font-weight: 400;
            color: #3b3b3b;
        }

        .error-card p {
            margin: 1.25rem 0 1.5rem;
            line-height: 1.6;
        }

        .error-card img {
            max-width: 220px;
            margin: 0.5rem auto 1.25rem;
            display: block;
        }

        .error-card a {
            display: inline-block;
            padding: 0.65rem 1.15rem;
            background: #dd4814;
            color: #fff;
            border-radius: 0.55rem;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 12px 15px rgba(221, 72, 20, 0.2);
            transition: transform 120ms ease, box-shadow 120ms ease;
        }

        .error-card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 20px rgba(221, 72, 20, 0.25);
        }

        .error-card small {
            display: block;
            margin-top: 1.75rem;
            color: rgba(0, 0, 0, 0.55);
        }
    </style>
</head>

<body class="" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <?= view('/partials/site_loader') ?>
    <div class="error-card container text-center">
        <h1>404</h1>
        <h2>Sorry for the inconvenience</h2>

        <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcHJuMmZxd3J1MGZncjB3MHpvMHNyMW9jaWVtZGFqanp4YTN1eGd6NCZlcD12MV9naWZzX3NlYXJjaCZjdD1n/8L0Pky6C83SzkzU55a/giphy.gif" alt="404" loading="lazy" />

        <p>
            <?php if (ENVIRONMENT !== 'production') : ?>
                <?= nl2br(esc($message)) ?>
            <?php else : ?>
                The page you're looking for can't be found.<br>
                It may have moved or no longer exists.
            <?php endif; ?>
        </p>

        <a href="/admin/dashboard">Go back home</a>

        <small>Need help? Contact support or try again later.</small>
    </div>
</body>

</html>