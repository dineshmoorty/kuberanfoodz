<?php
// Site-wide page loader overlay with company logo.
// This partial can be included at the top of the <body> for every page.
?>

<style>
  /* Loader overlay */
  #site-loader {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.92);
    z-index: 99999;
    opacity: 1;
    transition: opacity 250ms ease;
    pointer-events: all;
  }

  #site-loader.hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
  }

  #site-loader .loader-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.85);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
  }

  #site-loader .loader-logo {
    max-height: 70px;
    width: auto;
    display: block;
  }

  #site-loader .loader-spinner {
    width: 48px;
    height: 48px;
    border: 5px solid rgba(0, 0, 0, 0.1);
    border-top-color: rgba(221, 72, 20, 1);
    border-radius: 50%;
    animation: site-loader-spin 1s linear infinite;
  }

  @keyframes site-loader-spin {
    to {
      transform: rotate(360deg);
    }
  }
</style>

<div id="site-loader" aria-hidden="true">
  <div class="loader-inner">
    <img
      class="loader-logo"
      src="<?= esc(isset($company_logo) && $company_logo ? $company_logo : (function_exists('base_url') ? base_url('/images/logo.png') : '/images/logo.png')) ?>"
      alt="Loading..."
      loading="lazy" />
    <div class="loader-spinner" aria-hidden="true"></div>
  </div>
</div>

<script>
  (function() {
    var loader = document.getElementById('site-loader');
    if (!loader) return;

    var hide = function() {
      loader.classList.add('hidden');
    };

    var show = function() {
      loader.classList.remove('hidden');
    };

    // Hide once page finishes loading.
    if (document.readyState === 'complete') {
      hide();
    } else {
      window.addEventListener('load', hide, {
        once: true
      });
    }

    // Show immediately when navigation or form submit happens.
    document.addEventListener('click', function(event) {
      var link = event.target.closest('a[href]');
      if (!link) return;
      var href = link.getAttribute('href');
      if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
      if (link.target === '_blank' || link.hasAttribute('download')) return;
      show();
    });

    document.addEventListener('submit', function() {
      show();
    });

    // Fallback: hide if something goes wrong.
    setTimeout(hide, 5000);
  })();
</script>