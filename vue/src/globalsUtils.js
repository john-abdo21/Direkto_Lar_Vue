Date.prototype.getWeek = function() {
  var onejan = new Date(this.getFullYear(), 0, 1);
  return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
};

Date.prototype.getYearAndMonth = function() {
  const year = this.getFullYear();
  const month = this.toLocaleString('default', { month: 'short' }); // 'short' para abreviaturas como "Nov", 'long' para "November"
  return `${year} ${month}`;
};



Date.prototype.getYearAndMonthNumber = function() {
  const year = this.getFullYear();
  const month = this.getMonth() + 1; // Los meses en JavaScript van de 0 a 11, por eso sumamos 1
  return `${year}${month.toString().padStart(2, '0')}`; // Aseguramos que el mes tenga dos dígitos
};

String.prototype.convertToYearAndMonth = function() {
  const year = this.slice(0, 4);
  const month = parseInt(this.slice(4, 6)) - 1; // Restamos 1 porque los meses en JavaScript van de 0 a 11
  const date = new Date(year, month);
  const monthName = date.toLocaleString('default', { month: 'short' }); // 'short' para abreviaturas como "Nov", 'long' para "November"
  return `${year} ${monthName}`;
};
