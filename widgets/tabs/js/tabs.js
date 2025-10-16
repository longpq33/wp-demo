(function(){
  function init(container){
    var nav = container.querySelector('.msb-tabs-nav');
    if (!nav) return;
    var btns = Array.prototype.slice.call(nav.querySelectorAll('.msb-tab-btn'));
    var panels = Array.prototype.slice.call(container.querySelectorAll('.msb-tab-panel'));

    function activate(id){
      btns.forEach(function(b){
        var on = b.getAttribute('data-tab') === id;
        b.classList.toggle('is-active', on);
        b.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      panels.forEach(function(p){
        var on = p.id === ('panel-' + id);
        p.classList.toggle('is-active', on);
        if (on) p.removeAttribute('hidden'); else p.setAttribute('hidden','');
      });
    }

    nav.addEventListener('click', function(e){
      var btn = e.target.closest('.msb-tab-btn');
      if (!btn) return;
      var id = btn.getAttribute('data-tab');
      activate(id);
      if (history.replaceState) history.replaceState(null, '', '#tab-' + id);
      else location.hash = 'tab-' + id;
    });

    // Initialize: check hash first, fallback to first tab
    var firstTabId = btns.length > 0 ? btns[0].getAttribute('data-tab') : null;
    var m = (location.hash||'').match(/#tab-([A-Za-z0-9_\-]+)/);
    
    if (m) {
      // Check if the tab from hash exists
      var hashTabExists = btns.some(function(btn) {
        return btn.getAttribute('data-tab') === m[1];
      });
      if (hashTabExists) {
        activate(m[1]);
      } else {
        // Hash tab doesn't exist, use first tab
        if (firstTabId) activate(firstTabId);
      }
    } else {
      // No hash, use first tab
      if (firstTabId) activate(firstTabId);
    }
  }

  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.msb-sow-tabs').forEach(init);
  });
})();


