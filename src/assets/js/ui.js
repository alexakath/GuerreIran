document.addEventListener('DOMContentLoaded', function(){
  var sidebar = document.getElementById('sidebar');
  var overlay = document.getElementById('sidebarOverlay');
  var btn = document.getElementById('sidebarToggle');
  var close = document.getElementById('sidebarClose');
  var searchToggle = document.getElementById('searchToggle');

  function openSidebar(){
    if(!sidebar) return;
    sidebar.classList.add('open');
    overlay.classList.add('visible');
    sidebar.setAttribute('aria-hidden','false');
    overlay.setAttribute('aria-hidden','false');
  }
  function closeSidebar(){
    if(!sidebar) return;
    sidebar.classList.remove('open');
    overlay.classList.remove('visible');
    sidebar.setAttribute('aria-hidden','true');
    overlay.setAttribute('aria-hidden','true');
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
