console.log("calendario.js cargado");

// Este script inicializa el calendario y pide los eventos al archivo PHP

document.addEventListener('DOMContentLoaded', function () {

  console.log("Iniciando FullCalendar...");
  // Seleccionamos el contenedor del calendario
  let calendarEl = document.getElementById('calendar');

  // Creamos el calendario
  let calendar = new FullCalendar.Calendar(calendarEl, {
    // locale: 'esLocale', // idioma español
    initialView: 'dayGridMonth', // vista inicial (mes)

    // Cargamos los eventos desde nuestro PHP
    events: '/saludigital/saludigital/php/usuario/eventoscalendario.php',

    eventTimeFormat: { // like '14:30:00'
    hour: '2-digit',
    minute: '2-digit',
    meridiem: 'short'
    },

    // Cuando hago clic en un evento, muestro un popup con más detalles
    eventClick: function(info) {
      swal({
        title: "Doctor: " + info.event.extendedProps.doctor,
        text: "Servicio: " + info.event.extendedProps.servicio + "\n" +
              "Horario: " + info.event.start.toLocaleString(),
        icon: "info",
        buttons: {
          modify: {
            text: "Modificar",
            value: "modify"
          },
          delete: {
            text: "Eliminar",
            value: "delete"
          },
          cancel: "Cancelar"
        }
      }).then((value) => {
        if (value === "modify") {
          window.location.href = '/saludigital/saludigital/php/usuario/modificarturno.php?id=' + info.event.id;
        } else if (value === "delete") {
          swal({
            title: "¿Estás seguro?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            buttons: ["Cancelar", "Eliminar"],
            dangerMode: true
          }).then((shouldDelete) => {
            if (shouldDelete) {
              fetch('/saludigital/saludigital/php/usuario/eliminarturnos.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + info.event.id
              }).then(response => response.json())
                .then(data => {
                  if (data.success) {
                    swal("Eliminado", "El turno ha sido eliminado", "success");
                    calendar.refetchEvents();
                  } else {
                    swal("Error", data.message, "error");
                  }
                });
            }
          });
        }
      });
    }
  });                           

  // Renderizamos el calendario para que se vea en pantalla
  calendar.render();
});