import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    // Elementos del DOM
    const form = document.getElementById('filtro-form');
    const contenedor = document.getElementById('contenedor-productos');
    const resetBtn = document.getElementById('reset-btn');

    // Función para renderizar productos
    function renderizarProductos(productos) {
        let html = '';
        
        if (productos.length === 0) {
            html = '<p>No hay productos registrados en esta categoría.</p>';
        } else {
            html += '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">';
            
            productos.forEach(producto => {
                html += `
                    <div class="col" data-producto-id="${producto.id}">
                        <div class="card h-100 shadow-sm">
                            ${producto.imagen ? 
                                `<a href="/productos/${producto.id}">
                                    <img src="/${producto.imagen}" class="card-img-top" alt="${producto.nombre}" style="object-fit: cover; height: 300px;">
                                </a>` 
                                : ''
                            }
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="/productos/${producto.id}" class="text-decoration-none text-dark">
                                        ${producto.nombre}
                                    </a>
                                </h5>
                                <p class="card-text fw-bold mb-2">${producto.precio} €</p>
                                <p class="card-text text-muted">${producto.categoria?.nombre ?? 'Sin categoría'}</p>
                                <div class="mt-auto">
                                    <a href="/productos/${producto.id}" class="btn btn-info btn-sm w-100 mb-1">Ver</a>
                                    <a href="/pedidos/create?producto_id=${producto.id}" class="btn btn-success btn-sm w-100 mb-1">Realizar pedido</a>
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
            
            html += '</div>';
        }
        
        contenedor.innerHTML = html;
    }

    // Evento para filtrar productos
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const categoriaId = document.getElementById('categoria_id').value;
            
            fetch(`/productos-filtrados?categoria_id=${categoriaId}`)
                .then(res => res.json())
                .then(productos => renderizarProductos(productos))
                .catch(err => {
                    console.error(err);
                    contenedor.innerHTML = '<p>Error al filtrar productos.</p>';
                });
        });
    }

    // Evento para resetear filtro
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            document.getElementById('categoria_id').value = '';
            
            fetch(`/productos-filtrados`)
                .then(res => res.json())
                .then(productos => renderizarProductos(productos))
                .catch(err => {
                    console.error(err);
                    contenedor.innerHTML = '<p>Error al cargar productos.</p>';
                });
        });
    }

    // Eliminación con AJAX
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.matches('form[action*="/productos/"]')) {
            e.preventDefault();
            
            const form = e.target;
            
            if (!confirm('¿Estás seguro de eliminar este producto?')) return;
            
            const csrfToken = form.querySelector('input[name="_token"]').value;
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    '_method': 'DELETE',
                    '_token': csrfToken
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const card = form.closest('.col');
                    card.remove();
                    
                    const contenedor = document.getElementById('contenedor-productos');
                    if (contenedor && !contenedor.querySelector('.col')) {
                        contenedor.innerHTML = '<p>No hay productos registrados.</p>';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el producto');
            });
        }
    });
});
