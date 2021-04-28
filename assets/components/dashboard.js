document.querySelectorAll(".recentResponseTime").forEach(item => {
    let time = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    let timeServer = item.dataset.points.split(",");

    let responseTimeServerDate = item.dataset.points;
    const arr = ['cos', [1]]

    let responseTimeServer = [];
    for (let i = 1; i < timeServer.length; i += 2) {
        responseTimeServer.push(parseFloat(timeServer[i]));
    }

    let chart = new Chart(item, {
        type: 'line',
        data: {
            labels: time,
            datasets: [
                {
                    data: responseTimeServer
                }
            ]
        }
    });
});


