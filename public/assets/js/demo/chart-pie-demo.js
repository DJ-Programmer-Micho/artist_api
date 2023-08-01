// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = "Nunito"),
  '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: "doughnut",
  data: {
    labels: [
      "قلبي يحبك",
      "تريد نرجع",
      "مشكلتي",
      "حياتي مبهدله",
      "ابشرك",
      "بلا مطرود",
      "بغيابك",
      "اتبوس ايدينا",
      "العلاسة",
    ],
    datasets: [
      {
        data: [88, 11, 6, 2, 2, 1, 1, 1, 1],
        backgroundColor: [
          "#4e73df",
          "#1cc88a",
          "#36b9cc",
          "#cc0000",
          "#FF3333",
          "#ff9933",
          "#ffff33",
          "#99ff33",
          "#335500",
        ],
        hoverBackgroundColor: [
          "#2e59d9",
          "#17a673",
          "#2c9faf",
          "#ee2222",
          "#ee8822",
          "#eeee22",
          "#88ee22",
          "#224400",
        ],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      },
    ],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: "#dddfeb",
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: true,
    },
    cutoutPercentage: 80,
  },
});
