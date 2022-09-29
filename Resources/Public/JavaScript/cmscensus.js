/**
 * Main JavaScript for Frontend Module
 */

$(document).ready(
    function ($) {
        var Module = {};

        // Initialize
        Module.initialize = function () {
            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                success: function (response) {
                    Module.renderChartCmsPerCategoryUrls('cms-per-category-urls', response.cmsPerCategoryUrls.cmsUrlsCounts, response.cmsPerCategoryUrls.cmsLabels, response.cmsPerCategoryUrls.cmsColors, response.cmsPerCategoryUrls.cmsUrlsPercentages);
                }
            });
        }

        // Render "Urls per CMS" chart
        Module.renderChartCmsPerCategoryUrls = function (id, data, labels, colors, percentages) {

            // set Colors
            var borderColor = [];
            var backgroundColor = [];
            var countPercentage = [];
            for (var i in data) {
                borderColor.push("rgb(" + 150 + "," + 150 + "," + 150 + ", 0.2)");
                backgroundColor.push(colors[i]);
                countPercentage[labels[i]] = percentages[i];
            }

            var pie = document.getElementById(id).getContext("2d");
            const chart = new Chart(pie, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: backgroundColor
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            align: 'center'
                        },
                        title: {
                            display: false,
                            text: 'CMS'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.label + ' - ' + ctx.formattedValue + ' (' + countPercentage[ctx.label]  + '%)';
                                }
                            }
                        }
                    }
                }
            });
        };

        // Initialize
        $(document).ready(function () {
            Module.initialize();
        });
    }
);
