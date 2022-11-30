/**
 * Main JavaScript for Frontend Module
 */

$(document).ready(
    function ($) {

        //Bar Chart
        if(document.getElementById('versionLabel')){
            var post = document.getElementById('versionLabel').getAttribute('value');
            var label = JSON.parse(post.replace(/\'/g, '"'));
            var post1 = document.getElementById('versionDataset').getAttribute('value');
            var dataset = JSON.parse(post1.replace(/\'/g, '"'));
            var data = {
                labels: label,
                datasets: dataset
            };              
            new Chart('chart', {
                type: 'bar',
                data: data
            });
        }
        
        var listUrl = document.getElementsByClassName('listUrl');
        if(listUrl.length > 0) {
            for (let i = 0; i < listUrl.length; i++) {
            listUrl[i].addEventListener("click", function (e) {
                document.getElementById("graph-container").style.display = "block"; 
                var url = this.getAttribute("href");
                var xhr = new XMLHttpRequest();
                xhr.open("GET", url);
                xhr.onreadystatechange = function (data) {
                    if (this.readyState == 4 && this.status == 200) {
                        var parser = new DOMParser();
                        var xmlDoc = parser.parseFromString(xhr.responseText, "text/html");
                        var post = xmlDoc.getElementById('versionListLabel').getAttribute('value');
                        var label = JSON.parse(post.replace(/\'/g, '"'));
                        var post1 = xmlDoc.getElementById('versionListDataset').getAttribute('value');
                        var dataset = JSON.parse(post1.replace(/\'/g, '"'));
                        var data = {
                            labels: label,
                            datasets: dataset
                        };
                        $('#chartList').remove(); // this is my <canvas> element
                        $('#graph-container').append('<canvas id="chartList"><canvas>');
                        var myChart = new Chart('chartList', {
                            type: 'bar',
                            data: data
                        }); 
                    }
                };
                xhr.send();
                e.preventDefault();
            });
            }
        }

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
