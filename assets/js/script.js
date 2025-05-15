jQuery(document).ready(function ($) {
  // 1. Obtener todos los eventos
  const eventosContainer = $(".eventos-grid");
  if (!eventosContainer.length) return;

  const eventos = $(".evento-card").get(); // Convertir a array

  // 2. Función para extraer fecha (último mes + año)
  function getFechaOrden(evento) {
    const $evento = $(evento);
    const texto = $evento.find(".evento-temporada").text().trim().toLowerCase();

    // Extraer año (último número de 4 dígitos)
    const añoMatch = texto.match(/(20\d{2}|\d{4})/);
    const año = añoMatch ? parseInt(añoMatch[0]) : new Date().getFullYear();

    // Extraer todos los meses mencionados
    const meses =
      texto.match(
        /(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)/g
      ) || [];
    const ultimoMes = meses[meses.length - 1] || "enero"; // Usar último mes o enero por defecto

    // Convertir mes a número (0-11)
    const mesNumero =
      {
        enero: 0,
        febrero: 1,
        marzo: 2,
        abril: 3,
        mayo: 4,
        junio: 5,
        julio: 6,
        agosto: 7,
        septiembre: 8,
        octubre: 9,
        noviembre: 10,
        diciembre: 11,
      }[ultimoMes] || 0;

    return new Date(año, mesNumero, 1).getTime();
  }

  // 3. Ordenar eventos (más cercanos primero)
  eventos.sort((a, b) => getFechaOrden(a) - getFechaOrden(b));

  // 4. Reinsertar en el DOM
  eventosContainer.empty().append(eventos);
});
