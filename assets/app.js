import './styles/app.css';
import './bootstrap';


document.querySelectorAll(".recentResponseTime").forEach(item => {
    let time = [0, 1, 2, 3, 4, 5];
    let timeServer = item.dataset.points.split(",");

    let timeServerAnswer = [];
    for (let i = 0; i < timeServer.length; i++) {
        timeServerAnswer.push(parseFloat(timeServer[i]));
    }

    var responseTimeChart = new Chart(item, {
        type: 'line',
        data: {
            labels: time,
            datasets: [
                {
                    data: timeServerAnswer
                }
            ]
        }
    });
});



