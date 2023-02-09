<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app">
        <h1>My Lord</h1>
        <p>Day: {{ $day }}</p>
        <p>Month: {{ $month }}</p>
        <p>Total: {{ $total }}</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            mylord(lord, expired_at);
        })

        // localStorage.clear();
        let lord = localStorage.getItem('lord');
        let expired_at = localStorage.getItem('expired_at');

        const mylord = (lord, expired_at) => {
            let now = Math.floor(Date.now()/1000);
            if (expired_at && (now < expired_at)) {
                console.log('Nothing to do, my lord.')
                return;
            }

            if (lord && lord.length == 32) {
                axios.post('/api/lord', {lord: lord}).then(response => {
                    localStorage.setItem('expired_at', response.data.time);
                    console.log(response.data.msg)
                });

                return;
            }

            FingerprintJS.load().then(fp => fp.get()).then(result => {
                localStorage.setItem('lord', result.visitorId)
                return result.visitorId
            }).then((visitorId) => {
                axios.post('/api/lord', {lord: visitorId}).then(response => {
                    localStorage.setItem('expired_at', response.data.time);
                    console.log(response.data.msg)
                });
            });
        }
    </script>
</body>
</html>
