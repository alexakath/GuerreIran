document.addEventListener('DOMContentLoaded', function(){
  var sidebar = document.getElementById('sidebar');
  var overlay = document.getElementById('sidebarOverlay');
  var btn = document.getElementById('sidebarToggle');
  var close = document.getElementById('sidebarClose');
  var searchToggle = document.getElementById('searchToggle');

  function openSidebar(){
    if(!sidebar) return;
    sidebar.classList.add('open');
    overlay.classList.add('show');
    sidebar.setAttribute('aria-hidden','false');
    overlay.setAttribute('aria-hidden','false');
    if(btn) btn.setAttribute('aria-expanded','true');
  }
  function closeSidebar(){
    if(!sidebar) return;
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
    sidebar.setAttribute('aria-hidden','true');
    overlay.setAttribute('aria-hidden','true');
    if(btn) btn.setAttribute('aria-expanded','false');
  }

  if(btn) btn.addEventListener('click', openSidebar);
  if(close) close.addEventListener('click', closeSidebar);
  if(overlay) overlay.addEventListener('click', closeSidebar);

  if(searchToggle){
    searchToggle.addEventListener('click', function(){
      var s = document.getElementById('s');
      if(s){ s.focus(); }
      openSidebar(); // open sidebar to show search on small screens
    });
  }
});

// optional: allow pressing '/' to focus global search input
document.addEventListener('keydown', function(e){
  if(e.key === '/' && !e.metaKey && !e.ctrlKey && !e.altKey){
    var s = document.getElementById('s');
    if(s){ e.preventDefault(); s.focus(); }
  }
});
