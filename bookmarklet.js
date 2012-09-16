(function() {
	var d = document,
		b = d.body,
		l,
		e = encodeURIComponent,
		ce = 'createElement',
		ac = 'appendChild',
		de = 'documentElement',
		q = prompt("Instaswap\n\nSend this page to…");
	if (q) {
		if (!b) {
			b = d[ce]('body');
			if (!d[de]) {
				return;
			}
			d[de][ac](b);
		}
		l = d[ce]('script');
		l.setAttribute('src', 'http://instaswap.sidnicio.us/api?key=api_key&target=' + e(q) + '&url=' + e(d.location.href));
		b[ac](l);
	}
})();
