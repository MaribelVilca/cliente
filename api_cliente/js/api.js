let debounceTimer;

async function buscarHoteles() {
    const search = document.getElementById('search').value.trim();
    const token = document.getElementById('token').value;
    const resultadosDiv = document.getElementById('resultados');

    // Limpiar resultados si el campo está vacío
    if (!search) {
        resultadosDiv.innerHTML = '';
        return;
    }

    // Mostrar loading
    resultadosDiv.innerHTML = `
        <div class="loading">
            <i class="fas fa-spinner fa-spin"></i> Buscando hoteles...
        </div>
    `;

    // Cancelar la búsqueda anterior si existe
    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }

    // Esperar 500ms después de que el usuario deje de escribir
    debounceTimer = setTimeout(async () => {
        try {
            const formData = new FormData();
            formData.append('token', token);
            formData.append('search', search);

            const response = await fetch('api_handler.php?action=buscarHoteles', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.status) {
                Swal.fire({
                    icon: data.type || 'error',
                    title: data.type === 'error' ? 'Error' : 'Advertencia',
                    text: data.msg,
                    confirmButtonColor: '#6c8ea4'
                });
                resultadosDiv.innerHTML = '';
                return;
            }

            if (data.data.length === 0) {
                resultadosDiv.innerHTML = `
                    <div class="no-results">
                        <i class="fas fa-info-circle"></i> No se encontraron hoteles con el criterio: "<strong>${search}</strong>"
                    </div>
                `;
                return;
            }

            let html = '';
            data.data.forEach(hotel => {
                // Resaltar las coincidencias en los resultados
                const highlight = (text) => {
                    if (!text) return '';
                    const regex = new RegExp(search, 'gi');
                    return text.replace(regex, match => `<mark>${match}</mark>`);
                };

                html += `
                    <div class="hotel-card">
                        <div class="hotel-header">
                            ${hotel.imagen ? `<img src="${hotel.imagen}" alt="${hotel.nombre}" class="hotel-image">` : '<div class="hotel-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;"><i class="fas fa-hotel" style="font-size: 2rem; color: #ccc;"></i></div>'}
                            <div class="hotel-info">
                                <h3 class="hotel-nombre">${highlight(hotel.nombre)}</h3>
                                <p class="hotel-direccion">${highlight(hotel.direccion)}</p>
                            </div>
                        </div>
                        <div class="hotel-details">
                            <p><i class="fas fa-phone"></i> ${hotel.telefono || 'No especificado'}</p>
                            <p><i class="fas fa-dollar-sign"></i> Precio promedio: ${hotel.precio_promedio || 'No especificado'}</p>
                            <p><i class="fas fa-concierge-bell"></i> Servicios: ${highlight(hotel.servicios || 'No especificado')}</p>
                            <p><i class="fas fa-map-marker-alt"></i> <a href="${hotel.ubicacion}" target="_blank">Ver en mapa</a></p>
                        </div>
                    </div>
                `;
            });

            resultadosDiv.innerHTML = html;

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al buscar los hoteles.',
                confirmButtonColor: '#6c8ea4'
            });
            resultadosDiv.innerHTML = '';
        }
    }, 500); // Esperar 500ms
}
