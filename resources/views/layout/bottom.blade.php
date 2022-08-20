<script>
    function paramsToJson(url) {
        var params = url.substring(url.indexOf("?"));
        var jsonData = "{";

        if (url.includes("?")) {
            params = params.replace("?", "");
            params = params.split("&");
            for (var i = 0; i < params.length; i++) {
                var d = params[i];
                d = d.split("=");
                jsonData += "\"" + d[0] + "\":\"" + d[1] + "\",";
            }
            jsonData = jsonData.slice(0, -1);
        } else {
            params = "";
        }
        jsonData += "}";
        return jsonData;
    }

    function getDate() {
        var m = new Date();
        var dateString =
            m.getUTCFullYear() + "-" +
            ("0" + (m.getUTCMonth() + 1)).slice(-2) + "-" +
            ("0" + m.getUTCDate()).slice(-2);
        return dateString;
    }

    function toChartV2(type, data, idChart) {
        if (type === 'bar') {
            const config = {
                type: type,
                data: {
                    labels: data.labels,
                    datasets: data.datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };

            const chart = new Chart(
                document.getElementById(idChart),
                config
            );
        }
    }
</script>
</body>

</html>