/* dashboard.js - User dashboard (Home) page-specific scripts */

var earningsChartData = JSON.parse(document.getElementById('earnings-chart-data').textContent);

function setEarningsChartRange(range, chipEl) {
    var data = earningsChartData[range];
    if (!data) {
        return;
    }

    document.getElementById('earnings-line-path').setAttribute('d', data.line);
    document.getElementById('earnings-fill-path').setAttribute('d', data.fill);
    document.getElementById('chart-total-earned').innerText = '$' + data.totalEarned;
    document.getElementById('chart-tasks-completed').innerText = data.tasksCompleted;

    document.querySelectorAll('.chart-range-chip').forEach(function (chip) {
        chip.classList.remove('active');
    });
    chipEl.classList.add('active');
}

$(document).ready(function () {
    $('.customer-logos').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 2.2
            }
        }]
    });
});
