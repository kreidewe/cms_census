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

        if(document.getElementById('cmsSearch')){
            document.getElementById('cmsSearch').addEventListener('submit', function(evt){
                // let formData = new FormData(this);
                const filterForm = document.getElementById("cmsSearch");
                var data = new FormData(filterForm);
                var url = filterForm.getAttribute("action");
                const urlParams = new URLSearchParams(url);
                var xhr = new XMLHttpRequest();
                xhr.open("POST", url);
                xhr.onreadystatechange = function (data) {
                    if (this.readyState == 4 && this.status == 200) {
                        var parser = new DOMParser();
                        var xmlDoc = parser.parseFromString(xhr.responseText, "text/html");
                        document.getElementById("ajaxResult").innerHTML = xmlDoc.getElementById('ajaxData').innerHTML;

                        var columnSort = document.getElementsByClassName('columnSort');
                        if(columnSort.length > 0) {
                            for (let i = 0; i < columnSort.length; i++) {
                            columnSort[i].addEventListener("click", function (e) {
                                loadMore(this.getAttribute("href"),this.getAttribute('id'),this.dataset.show);
                                e.preventDefault();
                            });
                            }
                        }
                        var pagination = document.getElementsByClassName('article-load-more');
                        if(pagination.length > 0) {
                            for (let i = 0; i < pagination.length; i++) {
                            pagination[i].addEventListener("click", function (e) {
                                loadMore(this.getAttribute("href"),null,null);
                                e.preventDefault();
                            });
                            }
                        }
                    }
                };
                xhr.send(data);
                evt.preventDefault();
            });
        }
        function loadMore(url,hide,show) {
            // document.getElementById(hide).style.display = 'none';
            const filterForm = document.getElementById("cmsSearch");
            var data = new FormData(filterForm);
            const urlParams = new URLSearchParams(url);
            data.append('sortby',urlParams.get('tx_cmscensus_chartcmscensus[sortby]'));
            data.append('formate',urlParams.get('tx_cmscensus_chartcmscensus[formate]'));
            let xhr = new XMLHttpRequest();
            xhr.open("POST", url);

            xhr.onreadystatechange = function (data) {
              if (this.readyState == 4 && this.status == 200) {
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(xhr.responseText, "text/html");
                document.getElementById("ajaxResult").innerHTML = xmlDoc.getElementById('ajaxData').innerHTML;

                var columnSort = document.getElementsByClassName('columnSort');
                if(columnSort.length > 0) {
                    for (let i = 0; i < columnSort.length; i++) {
                    columnSort[i].addEventListener("click", function (e) {
                        loadMore(this.getAttribute("href"),this.getAttribute('id'),this.dataset.show);
                        e.preventDefault();
                    });
                    }
                }

                var pagination = document.getElementsByClassName('article-load-more');
                if(pagination.length > 0) {
                    for (let i = 0; i < pagination.length; i++) {
                        pagination[i].addEventListener("click", function (e) {
                            loadMore(this.getAttribute("href"),hide,show);
                            e.preventDefault();
                        });
                    }
                }
                if(hide){
                    document.getElementById(hide).style.display = 'none';
                    document.getElementById(show).style.display = 'block';
                }
              }
            };
            xhr.send(data);
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
