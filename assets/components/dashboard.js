document.querySelectorAll(".recentResponseTime").forEach(item => {

    let dateCreated = []
    let responseTimeServerDate = JSON.parse(item.dataset.points);
    let createdTime = 0

    if (responseTimeServerDate[0] !== undefined) {
        for (let i = 0; i < responseTimeServerDate.length; i++) {
            createdTime = 24 - responseTimeServerDate[i][0] / 60;
            dateCreated.push({x: createdTime, y: responseTimeServerDate[i][1], min: responseTimeServerDate[i][0], hour: responseTimeServerDate[i][0] / 60});
        }
    }

    chart = new Chart(item, {
        type: 'scatter',
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (context) {
                            let label;
                            if(context.raw.min < 60){
                               return label  = ['Created at ' + context.raw.min + ' min ago', 'Respone time = ' + context.raw.y] ;
                            }else{
                                return label = ['Created at ' +  Math.floor(context.raw.hour) + ' h ago', 'Respone time = ' + context.raw.y];
                            }
                        }
                    }

                },
                title: {
                    display: true,
                    text: 'Response time for server'
                }
            },
            scales: {
                x: {
                    min: 0,
                    max: 24,
                    ticks: {
                        stepSize: 2,
                        // For a category axis, the val is the index so the lookup via getLabelForValue is needed
                        callback: function (val, index) {
                            // Hide the label of every 2nd dataset
                            return 24 - val + ' h ago';
                        },
                    }

                }
            }
        },
        data: {
            datasets: [
                {
                    data: dateCreated
                }
            ]
        }
    })

});


