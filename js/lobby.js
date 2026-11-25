// ---------- CARRUSEL ----------
const carrusel = document.getElementById("carrusel");
const pista = document.getElementById("carrusel-pista");
const btnPrev = document.getElementById("btn-prev");
const btnNext = document.getElementById("btn-next");
const puntos = Array.from(document.querySelectorAll("#carrusel-puntos .punto"));

if (carrusel && pista && puntos.length > 0) {
    let indiceActual = 0;
    const totalSlides = puntos.length;
    let intervalo = null;
    let touchInicioX = 0;

    const irASlide = (nuevoIndice) => {
        indiceActual = (nuevoIndice + totalSlides) % totalSlides;
        pista.style.transform = `translateX(-${indiceActual * 100}%)`;
        puntos.forEach((punto, idx) => {
            punto.classList.toggle("activo", idx === indiceActual);
            punto.setAttribute("aria-selected", String(idx === indiceActual));
        });
    };

    const iniciarAutoplay = () => {
        detenerAutoplay();
        intervalo = setInterval(() => irASlide(indiceActual + 1), 5000);
    };

    const detenerAutoplay = () => {
        if (intervalo) {
            clearInterval(intervalo);
            intervalo = null;
        }
    };

    btnPrev?.addEventListener("click", () => {
        irASlide(indiceActual - 1);
        iniciarAutoplay();
    });

    btnNext?.addEventListener("click", () => {
        irASlide(indiceActual + 1);
        iniciarAutoplay();
    });

    puntos.forEach((punto) => {
        punto.addEventListener("click", () => {
            irASlide(Number(punto.dataset.index));
            iniciarAutoplay();
        });
    });

    // Pausa al pasar el cursor
    carrusel.addEventListener("mouseenter", detenerAutoplay);
    carrusel.addEventListener("mouseleave", iniciarAutoplay);

    // Deslizamiento táctil en teléfono/tablet
    carrusel.addEventListener("touchstart", (e) => {
        touchInicioX = e.touches[0].clientX;
        detenerAutoplay();
    }, { passive: true });

    carrusel.addEventListener("touchend", (e) => {
        const diff = e.changedTouches[0].clientX - touchInicioX;
        if (Math.abs(diff) > 40) {
            irASlide(diff < 0 ? indiceActual + 1 : indiceActual - 1);
        }
        iniciarAutoplay();
    }, { passive: true });

    iniciarAutoplay();
}

// ---------- CATÁLOGO DESDE MYSQL ----------
const gridProductos = document.getElementById("grid-productos");

const cargarCatalogo = async () => {
    if (!gridProductos) return;

    try {
        const respuesta = await fetch("productos_api.php");
        const datos = await respuesta.json();

        if (!datos.ok || !Array.isArray(datos.productos) || datos.productos.length === 0) {
            gridProductos.innerHTML = '<p class="estado-carga">No hay productos registrados todavía.</p>';
            return;
        }

        gridProductos.innerHTML = "";
        datos.productos.forEach((prod) => {
            const disponible = Number(prod.disponible) === 1;
            const precio = Number(prod.precio).toFixed(2);

            const tarjeta = document.createElement("article");
            tarjeta.className = "tarjeta-producto";

            const titulo = document.createElement("h3");
            titulo.textContent = prod.nombre;

            const pie = document.createElement("div");
            pie.className = "producto-pie";

            const spanPrecio = document.createElement("span");
            spanPrecio.className = "producto-precio";
            spanPrecio.textContent = `$${precio}`;

            const spanEstado = document.createElement("span");
            spanEstado.className = `badge-estado ${disponible ? "disponible" : "agotado"}`;
            spanEstado.textContent = disponible ? "Disponible" : "Agotado";

            pie.append(spanPrecio, spanEstado);
            tarjeta.append(titulo, pie);
            gridProductos.appendChild(tarjeta);
        });
    } catch {
        gridProductos.innerHTML = '<p class="estado-carga">No se pudo cargar el catálogo.</p>';
    }
};

cargarCatalogo();
