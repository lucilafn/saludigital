// Este script inicializa el calendario y pide los eventos al archivo PHP

document.addEventListener('DOMContentLoaded', function () {
  // Seleccionamos el contenedor del calendario
  let calendarEl = document.getElementById('calendar');

  // Creamos el calendario
  let calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth', // vista inicial (mes)
    locale: 'es', // idioma español

    // Cargamos los eventos desde nuestro PHP
    events: 'php/eventoscalendario.php',

    // Cuando hago clic en un evento, muestro un popup con más detalles
    eventClick: function(info) {
      alert(
        "Doctor: " + info.event.extendedProps.doctor + "\n" +
        "Servicio: " + info.event.extendedProps.servicio + "\n" +
        "Horario: " + info.event.start.toLocaleString()
      );
    }
  });                           

  // Renderizamos el calendario para que se vea en pantalla
  calendar.render();
});
