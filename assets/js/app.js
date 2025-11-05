// ===========================================================
// ✅ Script principal del panel Logística Global S.A.
// Controla el sidebar, el modo responsive, las alertas y la interacción del menú
// ===========================================================

document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("menu-toggle");
  const sidebar = document.getElementById("sidebar");
  const body = document.body;

  /* ============================================================
     SIDEBAR RESPONSIVE
  ============================================================ */
  if (toggleBtn && sidebar) {
    // Alternar visibilidad
    const toggleSidebar = () => {
      sidebar.classList.toggle("active");
      body.classList.toggle("sidebar-open");
    };

    // Click en el botón hamburguesa
    toggleBtn.addEventListener("click", toggleSidebar);

    // Click fuera del sidebar (cerrar en móvil)
    document.addEventListener("click", (e) => {
      const isClickInsideSidebar = sidebar.contains(e.target);
      const isClickOnButton = toggleBtn.contains(e.target);
      if (!isClickInsideSidebar && !isClickOnButton && sidebar.classList.contains("active")) {
        toggleSidebar();
      }
    });

    // Ajuste automático al cambiar tamaño de ventana
    window.addEventListener("resize", () => {
      if (window.innerWidth > 900) {
        sidebar.classList.remove("active");
        body.classList.remove("sidebar-open");
      }
    });

    // Cerrar con tecla Esc
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && sidebar.classList.contains("active")) {
        toggleSidebar();
      }
    });
  }

  /* ============================================================
     ALERTAS GLOBALES (Desvanecer automáticamente)
  ============================================================ */
  const alert = document.querySelector(".alert");
  if (alert) {
    // Desaparecer después de 4 segundos
    setTimeout(() => {
      alert.classList.add("fade-out");
      setTimeout(() => alert.remove(), 500);
    }, 4000);
  }
});
