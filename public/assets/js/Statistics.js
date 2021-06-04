
const dataOrderByDay = {

    categories: ['0','1','2','3','4','5','6','7',8,9],
    series: [
        {
            name: 'Coûts',
            data: vars.purchasedTotalByDay
        },
        {
            name: 'Ventes',
            data: vars.soldTotalByDay
        }

    ]
}

const optionsOrderByDay = {
    chart: {
        width: 500,
        height: 300
    },
    xAxis: {
        pointOnColumn: false,
        title: {
            text: 'Jour'
        }
    },
    yAxis: {
        title: 'Montant'
    }
};

console.log(vars);

const dataConnectionsByHourPieChart = {
    categories: ['Connection'],
    series: vars.connectionsByHour

}

const optionsConnectionsByHourPieChart = {
    tooltip: {
        offsetX: 30,
        offsetY: -100,
      },
    theme: {
        series: {
          colors: ["#4FB3BF"],
          lineWidth: 2,
          strokeStyle: '#FFFFFF',
        }
    },
    chart: {
        width: 300,
        height: 300
    },
    legend: {
        visible: false
    },
    series: {
        dataLabels: {
            visible: true,
            anchor: 'outer',
            textBubble: {
                visible: false
            }
        },
        radiusRange: {
            inner: '50%',
            outer: '100%',
        }
    }

};


const dataArticleBarChart = {
    categories: [...Array(10).keys()],
    series: [

        {
            name: 'Coûts',
            data: vars.articlePurchases
        },
        {
            name: 'Ventes',
            data: vars.articleSales
        }
    ]
}

const optionsArticleBarChart = {
    chart: {
        title: 'Article',
        width: 500,
        height: 300
    }
};


$(() => { // orders graph
    const chartOrderNode = document.getElementById('chartOrder');
    const chartOrder = toastui.Chart.lineChart({el: chartOrderNode, data: dataOrderByDay, options: optionsOrderByDay});

    // log connexion
    const connectionsByHourPieChartNode = document.getElementById('chartConnexionLog');
    const chartConnexionLog = toastui.Chart.pieChart({
        el: connectionsByHourPieChartNode,
        data: dataConnectionsByHourPieChart,
        options: optionsConnectionsByHourPieChart
    });

    // article bar chart
    const chartArticleNode = document.getElementById('chartArticle');
    const chartArticle = toastui.Chart.columnChart({el: chartArticleNode, data: dataArticleBarChart, options: optionsArticleBarChart});

});
