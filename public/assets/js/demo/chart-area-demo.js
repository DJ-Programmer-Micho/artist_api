// // Set new default font family and font color to mimic Bootstrap's default styling
// (Chart.defaults.global.defaultFontFamily = "Nunito"),
//     '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
// Chart.defaults.global.defaultFontColor = "#858796";

// function number_format(number, decimals, dec_point, thousands_sep) {
//     // *     example: number_format(1234.56, 2, ',', ' ');
//     // *     return: '1 234,56'
//     number = (number + "").replace(",", "").replace(" ", "");
//     var n = !isFinite(+number) ? 0 : +number,
//         prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
//         sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
//         dec = typeof dec_point === "undefined" ? "." : dec_point,
//         s = "",
//         toFixedFix = function (n, prec) {
//             var k = Math.pow(10, prec);
//             return "" + Math.round(n * k) / k;
//         };
//     // Fix for IE parseFloat(0.55).toFixed(0) = 0;
//     s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
//     if (s[0].length > 3) {
//         s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
//     }
//     if ((s[1] || "").length < prec) {
//         s[1] = s[1] || "";
//         s[1] += new Array(prec - s[1].length + 1).join("0");
//     }
//     return s.join(dec);
// }

// // Area Chart Example
// var ctx = document.getElementById("myAreaChart");
// var myLineChart;

// function updateChart(yearData) {
//     if (myLineChart) {
//         myLineChart.destroy();
//     }
//     myLineChart = new Chart(ctx, {
//         type: "line",
//         data: {
//             labels: yearData.labels,
//             datasets: [
//                 {
//                     label: "Earnings",
//                     lineTension: 0.3,
//                     backgroundColor: "rgba(78, 115, 223, 0.05)",
//                     borderColor: "rgba(78, 115, 223, 1)",
//                     pointRadius: 3,
//                     pointBackgroundColor: "rgba(78, 115, 223, 1)",
//                     pointBorderColor: "rgba(78, 115, 223, 1)",
//                     pointHoverRadius: 3,
//                     pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
//                     pointHoverBorderColor: "rgba(78, 115, 223, 1)",
//                     pointHitRadius: 10,
//                     pointBorderWidth: 2,
//                     data: yearData.earnings,
//                 },
//             ],
//         },
//         options: {
//             maintainAspectRatio: false,
//             layout: {
//                 padding: {
//                     left: 10,
//                     right: 25,
//                     top: 25,
//                     bottom: 0,
//                 },
//             },
//             scales: {
//                 xAxes: [
//                     {
//                         time: {
//                             unit: "date",
//                         },
//                         gridLines: {
//                             display: false,
//                             drawBorder: false,
//                         },
//                         ticks: {
//                             maxTicksLimit: 7,
//                         },
//                     },
//                 ],
//                 yAxes: [
//                     {
//                         ticks: {
//                             maxTicksLimit: 5,
//                             padding: 10,
//                             // Include a dollar sign in the ticks
//                             callback: function (value, index, values) {
//                                 return "$" + number_format(value);
//                             },
//                         },
//                         gridLines: {
//                             color: "rgb(234, 236, 244)",
//                             zeroLineColor: "rgb(234, 236, 244)",
//                             drawBorder: false,
//                             borderDash: [2],
//                             zeroLineBorderDash: [2],
//                         },
//                     },
//                 ],
//             },
//             legend: {
//                 display: false,
//             },
//             tooltips: {
//                 backgroundColor: "rgb(255,255,255)",
//                 bodyFontColor: "#858796",
//                 titleMarginBottom: 10,
//                 titleFontColor: "#6e707e",
//                 titleFontSize: 14,
//                 borderColor: "#dddfeb",
//                 borderWidth: 1,
//                 xPadding: 15,
//                 yPadding: 15,
//                 displayColors: false,
//                 intersect: false,
//                 mode: "index",
//                 caretPadding: 10,
//                 callbacks: {
//                     label: function (tooltipItem, chart) {
//                         var datasetLabel =
//                             chart.datasets[tooltipItem.datasetIndex].label ||
//                             "";
//                         return (
//                             datasetLabel +
//                             ": $" +
//                             number_format(tooltipItem.yLabel)
//                         );
//                     },
//                 },
//             },
//         },
//     });
// }

// var yearData = {
//     2022: [0, 3.3, 54, 0.5, 18, 6.5, 120, 88, 230, 17, 112, 35],
//     2023: [11, 32, 45, 11.6, 23, 34, 56, 78, 90, 45, 67, 89],
//     // Add data for future years if available
// };

// updateChart({
//     labels: [
//         "Feb-22",
//         "Mar-22",
//         "Apr-22",
//         "May-22",
//         "Jun-22",
//         "Jul-22",
//         "Aug-22",
//         "Sep-22",
//         "Oct-22",
//         "Nov-22",
//         "Dec-22",
//         "Jan-23",
//     ],
//     earnings: yearData["2022"],
// });

// document.getElementById("yearSelect").addEventListener("change", function () {
//     var selectedYear = this.value;
//     var selectedYearData = yearData[selectedYear] || [];

//     updateChart({
//         labels: [
//             "Feb-" + selectedYear.substring(2),
//             "Mar-" + selectedYear.substring(2),
//             "Apr-" + selectedYear.substring(2),
//             "May-" + selectedYear.substring(2),
//             "Jun-" + selectedYear.substring(2),
//             "Jul-" + selectedYear.substring(2),
//             "Aug-" + selectedYear.substring(2),
//             "Sep-" + selectedYear.substring(2),
//             "Oct-" + selectedYear.substring(2),
//             "Nov-" + selectedYear.substring(2),
//             "Dec-" + selectedYear.substring(2),
//             "Jan-" + (parseInt(selectedYear.substring(2)) + 1),
//         ],
//         earnings: selectedYearData,
//     });
// });
