#!/usr/bin/env perl
use strict;
use warnings;

use Data::Dumper;

my %keys;
my %envs;

while (<>) {
    if (/^([^\.]+)\.(\S+)\s+=\s+(.*)/) {
        $envs{$1} //= {};
        $keys{$2} //= {};
        
        $envs{$1}{$2} = $3;       
        $keys{$2}{$1} = $3;
    }
}

foreach my $env (sort keys %envs) {
    foreach my $key (sort keys %{$envs{$env}}) {
        my $value = $envs{$env}{$key};
        foreach my $other_key (sort keys %{$envs{$env}}) {
            my $other_value = $envs{$env}{$other_key};
            next unless ($value and $other_value);
            next if ($value eq $other_value);
            if ($value =~ /\Q$other_value/ or $other_value =~ /\Q$value/) {
                print "$env/$key ($value) and $env/$other_key ($other_value) are conflicting\n";
            }
        }
    }
}
# foreach my $key (sort keys %keys) {
#     print "$key:\n";
#     foreach my $env (sort keys %{$keys{$key}}) {
#         my $value = $keys{$key}{$env};
#         printf "  %10s: %s\n", $env, $value;
#     }
# }
