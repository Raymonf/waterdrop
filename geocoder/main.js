const express = require('express');
const app = express();
const port = 3142;
const geocoder = require('offline-geocoder')({ database: 'db.sqlite' });

app.get('/lookup/:long/:lat', (req, res) => {
	geocoder.reverse(req.params.long, req.params.lat)
		.then(result => {
			if (typeof result.formatted !== undefined && result.formatted != null) {
				let name = result.formatted;

				if (name.includes(', United States')) {
					name = name.replace(', United States', '');
				}

				res.send({
					success: true,
					name
				});
			} else {
				res.send({
					success: false
				});
			}
		})
		.catch(error => {
			res.send({
				success: false,
				error
			});
		})
});

app.listen(port, () => console.log(`Listening on port ${port}!`));