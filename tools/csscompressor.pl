use CSS::Packer;
use strict;
use EV;

opendir(DIR, '.') || die('Cannot open directory');

warn 'CSS-compressor started';

my $w = EV::periodic 0, 2, 0, sub {
	my @styles = grep { /^[^.]+\.css$/i && -f $_ } readdir DIR;
	rewinddir DIR;
	for my $stylePath(@styles) {
		open NCCSS => "<$stylePath";
			my $buffer = do { local $/ ; <NCCSS>};
		close NCCSS;
		$stylePath =~s/css/min.css/gi;
		open STCSS => ">$stylePath";
			print STCSS CSS::Packer::minify(\$buffer, {
				compress            => 'minify',
				no_compress_comment => 0,
			}) if (length($buffer) > 0);
		close STCSS;
	}
};

my $q = EV::signal 'QUIT', sub {
	closedir DIR;
};

EV::loop;