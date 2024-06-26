const ProgressBarPlugin = {
    id: 'progressBar',
    afterDraw(chart, args, options) {
        const { ctx, chartArea: { top, bottom, left, right, width, height }, scales: { x, y } } = chart;
        const { data } = chart;

        ctx.save();

        const barHeight = height / y.ticks.length * data.datasets[0].barPercentage * data.datasets[0].categoryPercentage;

        data.datasets[0].data.forEach((datapoint, index) => {
            // Label text
            const fontSizeLabel = 12;
            ctx.font = `${fontSizeLabel}px sans-serif`;
            ctx.fillStyle = 'rgba(102, 102, 102, 1)';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'middle';
            ctx.fillText(data.labels[index], left, y.getPixelForValue(index) - fontSizeLabel - 5);

            // Value text
            const fontSizeDatapoint = 15;
            ctx.font = `${fontSizeDatapoint}px sans-serif`;
            ctx.fillStyle = 'rgba(102, 102, 102, 1)';
            ctx.textAlign = 'right';
            ctx.textBaseline = 'middle';
            ctx.fillText(datapoint, right, y.getPixelForValue(index) - fontSizeDatapoint - 5);

            // Background color progress bar
            ctx.beginPath();
            const borderColor = Array.isArray(data.datasets[0].borderColor) ? data.datasets[0].borderColor[index] : data.datasets[0].borderColor;
            ctx.fillStyle = borderColor || 'rgba(0, 0, 0, 1)';
            ctx.fillRect(left, y.getPixelForValue(index) - (barHeight / 2), (datapoint / Math.max(...data.datasets[0].data)) * width, barHeight);
            ctx.closePath();
        });

        ctx.restore();
    }
};

export default ProgressBarPlugin;

