const ProgressBarPlugin = {
    id: 'progressBar',
    afterDraw: function(chart, args, options) {
        const ctx = chart.ctx;
        const { ctx, chartArea: top, bottom, left, right, width, height}, 
            scales: {x,y} = chart;
        const datasets = chart.config.data.datasets;
        const dataset = datasets[0];

        ctx.save();

        // Iterate through each bar in the first dataset
        for (let i = 0; i < dataset.data.length; i++) {
            const model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
            const text = dataset.data[i];

            ctx.fillStyle = '#000';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';
            ctx.font = '12px Arial';

            // Display custom label above each bar
            ctx.fillText(text, model.x, model.y - 5);
        }

        ctx.restore();
    }
};

export default ProgressBarPlugin;
