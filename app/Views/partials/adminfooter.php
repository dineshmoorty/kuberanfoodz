    </div>

    </div>

    </main>

    <style>
      body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }

      main {
        flex: 1 0 auto;
        margin-bottom: 60px;
      }

      footer.footer-fixed {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 999;
        background-color: #84994f;
        color: #fff;
        padding: 0.6rem 0;
      }
    </style>

    <footer class="footer-fixed text-center w-100">
      <p class="mb-0">&copy; <?= date('Y') ?> <?= esc($company_name ?? 'Kuberan Foods Admin') ?></p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>

    </html>