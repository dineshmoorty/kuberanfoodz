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
    padding: 1.25rem;
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.85);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
  }

  #site-loader .loader-logo-wrapper {
    position: relative;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #site-loader .loader-ring {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 4px solid rgba(221, 72, 20, 0.22);
    border-top-color: rgba(221, 72, 20, 0.7);
    border-radius: 50%;
    animation: site-loader-spin 1s linear infinite;
  }

  #site-loader .loader-logo {
    max-height: 48px;
    width: auto;
    border-radius: 50%;
    z-index: 1;
    box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.88);
  }

  #site-loader .loader-spinner {
    display: none;
  }

  @keyframes site-loader-spin {
    to {
      transform: rotate(360deg);
    }
  }
</style>

<div id="site-loader" aria-hidden="true">
  <div class="loader-inner">
    <div class="loader-logo-wrapper">
      <div class="loader-ring" aria-hidden="true"></div>
      <img
        class="loader-logo"
        src="<?= esc(isset($company_logo) && $company_logo ? $company_logo : (function_exists('base_url') ? base_url('/images/logo.png') : '/images/logo.png')) ?>"
        alt="Loading..."
        loading="lazy" />
    </div>
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

    // Hide once the DOM is ready so image-heavy pages do not feel stuck.
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      hide();
    } else {
      document.addEventListener('DOMContentLoaded', hide, {
        once: true
      });
    }

    // Also hide again on full load as a safety net.
    window.addEventListener('load', hide, {
      once: true
    });

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
