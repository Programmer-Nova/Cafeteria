// ---------- MODAL DE ELIMINAR ----------
const modal       = document.getElementById("modal-eliminar");
const modalId     = document.getElementById("modal-id");
const modalTexto  = document.getElementById("modal-texto");
const modalCancel = document.getElementById("modal-cancelar");

if (modal) {
    const abrirModal = (id, nombre) => {
        modalId.value = id;
        modalTexto.textContent = `"${nombre}" se eliminará del catálogo. Esta acción no se puede deshacer.`;
        modal.hidden = false;
        modal.removeAttribute("hidden");
    };

    const cerrarModal = () => {
        modal.hidden = true;
        modal.setAttribute("hidden", "");
    };

    document.addEventListener("click", (e) => {
        const botonEliminar = e.target.closest(".btn-eliminar");
        if (botonEliminar) {
            abrirModal(botonEliminar.dataset.id, botonEliminar.dataset.nombre);
            return;
        }
        if (e.target === modal) {
            cerrarModal();
        }
    });

    if (modalCancel) {
        modalCancel.addEventListener("click", cerrarModal);
    }

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && !modal.hidden) cerrarModal();
    });
}

