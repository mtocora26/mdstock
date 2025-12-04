// Función para actualizar el envío y el total en el resumen del carrito (cart.php)
function actualizarEnvioYTotal() {
  var subtotal = 0;
  var subtotalSpan = document.getElementById('subtotal-value');
  if (subtotalSpan) {
    subtotal = parseFloat(subtotalSpan.textContent.replace('$', '').replace(',', '')) || 0;
  }

  var freeRadio = document.getElementById('free');
  var standardRadio = document.getElementById('standard');

  // Habilitar o deshabilitar envío gratis según el subtotal
  if (subtotal >= 50000) {
    // Subtotal cumple la condición: habilitar envío gratis y seleccionarlo automáticamente
    if (freeRadio) {
      freeRadio.disabled = false;
      freeRadio.checked = true;
    }
  } else {
    // Subtotal NO cumple la condición: deshabilitar envío gratis
    if (freeRadio) {
      freeRadio.disabled = true;
      // Si estaba seleccionado, cambiar a envío estándar
      if (freeRadio.checked && standardRadio) {
        standardRadio.checked = true;
      }
    }
  }

  // Obtener opción de envío seleccionada y corregir envío gratis
  var envio = 10000;
  var radios = document.getElementsByName('shipping');
  for (var i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      envio = parseInt(radios[i].value);
    }
  }
  // Si el radio de envío gratis está habilitado y seleccionado, el envío es 0
  if (freeRadio && freeRadio.checked && !freeRadio.disabled) {
    envio = 0;
  }

  // Actualizar valores en el DOM
  var shippingValue = document.getElementById('shipping-value');
  if (shippingValue) {
    shippingValue.textContent = '$' + Math.floor(envio);
  }

  var totalSpan = document.getElementById('total-value');
  if (totalSpan) {
    totalSpan.textContent = '$' + Math.floor(subtotal + envio);
  }

  var shippingInput = document.getElementById('shipping-input');
  if (shippingInput) {
    shippingInput.value = envio;
  }
}

// Función para actualizar el resumen en checkout.php
function actualizarResumenCheckout() {
  // Solo ejecutar si existen radios de envío (checkout con selección de método)
  var radios = document.getElementsByName('shipping');
  if (!radios || radios.length === 0) {
    // No hay radios de envío, no modificar valores calculados por PHP
    return;
  }

  var subtotalInput = document.getElementById('subtotal-checkout');
  var subtotal = subtotalInput ? parseFloat(subtotalInput.value) || 0 : 0;
  var freeRadio = document.getElementById('free');
  var standardRadio = document.getElementById('standard');

  // Habilitar/deshabilitar envío gratis según subtotal
  if (subtotal >= 50000) {
    if (freeRadio) {
      freeRadio.disabled = false;
      freeRadio.checked = true;
    }
  } else {
    if (freeRadio) {
      freeRadio.disabled = true;
      if (freeRadio.checked && standardRadio) {
        standardRadio.checked = true;
      }
    }
  }

  // Obtener opción de envío seleccionada
  var envio = 10000;
  for (var i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      envio = parseInt(radios[i].value);
    }
  }
  if (freeRadio && freeRadio.checked && !freeRadio.disabled) {
    envio = 0;
  }

  // Actualizar valores en el DOM
  var shippingValue = document.getElementById('shipping-value');
  if (shippingValue) {
    shippingValue.textContent = '$' + Math.floor(envio);
  }
  var totalValue = document.getElementById('total-value');
  if (totalValue) {
    totalValue.textContent = '$' + Math.floor(subtotal + envio);
  }
  var shippingInput = document.getElementById('shipping-input');
  if (shippingInput) {
    shippingInput.value = envio;
  }
}

// Inicializar listeners para los radios de envío y actualizar el total al cargar
document.addEventListener('DOMContentLoaded', function() {
  // Para cart.php
  var subtotalValue = document.getElementById('subtotal-value');
  if (subtotalValue) {
    actualizarEnvioYTotal();
    document.querySelectorAll('input[name="shipping"]').forEach(function(radio) {
      radio.addEventListener('change', actualizarEnvioYTotal);
    });
  }

  // Para checkout.php: deshabilitado para evitar sobrescribir valores PHP
  // var subtotalCheckout = document.getElementById('subtotal-checkout');
  // if (subtotalCheckout) {
  //   actualizarResumenCheckout();
  //   document.querySelectorAll('input[name="shipping"]').forEach(function(radio) {
  //     radio.addEventListener('change', actualizarResumenCheckout);
  //   });
  // }
});
// Función para actualizar el contador del carrito
function actualizarContadorCarrito() {
  fetch('/controller/CarritoAjaxController.php')
    .then(response => response.json())
    .then(data => {
      const badge = document.getElementById('cart-badge');
      const cartLink = badge ? badge.parentElement : document.querySelector('.header-action-btn[href="cart.php"]');
      if (data.cantidad > 0) {
        if (badge) {
          badge.textContent = data.cantidad;
          badge.style.display = '';
        } else if (cartLink) {
          // Crear el badge si no existe
          const newBadge = document.createElement('span');
          newBadge.className = 'badge';
          newBadge.id = 'cart-badge';
          newBadge.textContent = data.cantidad;
          cartLink.appendChild(newBadge);
        }
      } else {
        // Ocultar el badge si la cantidad es 0
        if (badge) {
          badge.style.display = 'none';
        }
      }
    })
    .catch(error => {
      console.error('Error al actualizar el carrito:', error);
      // Si la respuesta no es JSON válido, muestra un mensaje claro
      const badge = document.getElementById('cart-badge');
      if (badge) {
        badge.style.display = 'none';
      }
    });
}

// Función para manejar acciones del carrito con AJAX
function manejarAccionCarrito(form, event) {
  event.preventDefault();

  const formData = new FormData(form);
  const action = form.getAttribute('action');

  console.log('Enviando a:', action);
  console.log('Datos del formulario:', Object.fromEntries(formData));

  fetch(action, {
    method: 'POST',
    body: formData
  })
  .then(response => {
    console.log('Respuesta recibida:', response.status);
    // Recargar la página para reflejar los cambios
    window.location.reload();
  })
  .catch(error => {
    console.error('Error al actualizar el carrito:', error);
    // En caso de error, recargar la página de todas formas
    window.location.reload();
  });
}

// Actualizar el contador cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
  actualizarContadorCarrito();

  // Usar event delegation para manejar los formularios del carrito
  // Esto previene problemas cuando AOS u otros scripts manipulan el DOM
  document.body.addEventListener('submit', function(event) {
    const form = event.target;

    // Solo interceptar formularios del carrito
    if (form.action && form.action.includes('CarritoController.php')) {
      console.log('Formulario del carrito detectado:', form.action);
      // Dejar que el formulario se envíe normalmente
      // No prevenir el evento default
    }
  });

  console.log('Carrito inicializado');
});

// Actualizar el contador después de modificaciones del carrito
window.addEventListener('carritoActualizado', actualizarContadorCarrito);
