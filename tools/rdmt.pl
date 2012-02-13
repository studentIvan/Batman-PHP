$|=1;
use EV;
use IO::Socket;
use Config::IniFiles;

my ($server, $asyncTimer);
our (%dx, %control);
our $cId = 0;

my $cfg = new Config::IniFiles -file => 'rdmt.ini';
our $cfgPort = ($cfg->val( 'General', 'UsingPort' ))*1;
our $cfgTimer = ($cfg->val( 'General', 'LoopPerSeconds' ))*1;
our $cfgTimeout = ($cfg->val( 'General', 'ClientTimeoutSeconds' ))*1;
$cfgPort = ($cfgPort > 0) ? $cfgPort : 3308;
$cfgTimer = ($cfgTimer > 0) ? $cfgTimer : 60;
$cfgTimeout = ($cfgTimeout > 0) ? $cfgTimeout : 3;

$dx{server} = IO::Socket::INET->new(
    Proto => 'tcp',
    LocalPort => $cfgPort,
    Listen => SOMAXCONN,
    ReuseAddr => 1
);

die "can't setup server" unless $dx{server};

sub flushMemory {
    delete $dx{'gc'.$_[0]} if exists $dx{'gc'.$_[0]};
    close $dx{'client'.$_[0]} if exists $dx{'client'.$_[0]};
    delete $dx{'wr'.$_[0]} if exists $dx{'wr'.$_[0]};
    delete $dx{'lr'.$_[0]} if exists $dx{'lr'.$_[0]};
    delete $dx{'data'.$_[0]} if exists $dx{'data'.$_[0]};
    delete $dx{'wrblock'.$_[0]} if exists $dx{'wrblock'.$_[0]};
    delete $dx{'client'.$_[0]} if exists $dx{'client'.$_[0]};
}

$dx{w} = EV::io $dx{server}, EV::READ, sub {
    $cId = 0 if ($cId > 100_000_000_000_000); $cId++;
    $dx{'wrblock'.$cId} = 1;
    $dx{'client'.$cId} = $dx{server}->accept();
	
    $dx{'lr'.$cId} = EV::io $dx{'client'.$cId}, EV::READ, sub {
        my $e = sysread(
                $dx{'client'.$cId}, $dx{'data'.$cId}, 45
            ) if ($dx{'wrblock'.$cId} == 1);
        $dx{'wrblock'.$cId} = 2;
        if (defined $e or $dx{'wrblock'.$cId} == 1) {
            delete $dx{'wr'.$cId} if exists $dx{'wr'.$cId};
            $dx{'wr'.$cId} = EV::io $dx{'client'.$cId}, EV::WRITE, sub {
                if ($dx{'wrblock'.$cId} == 2) {
                    my ($key, $max) = split(':', $dx{'data'.$cId});
                    $control{$key} = 0 unless exists $control{$key};
                    $control{$key}++;
                    print {$dx{'client'.$cId}} ($control{$key} > $max) ? 0:1;
                    $dx{'wrblock'.$cId} = 1;
                }
            };
        }
		else {
            $dx{'wrblock'.$cId} = 0;
            flushMemory $cId;
        }
    };
	
    $dx{'gc'.$cId} = EV::timer $cfgTimeout, 0, sub {
        flushMemory $cId;
    };
};

my $asyncTimer = EV::periodic 0, $cfgTimer, 0, sub {
    %control = ();
};

EV::loop;