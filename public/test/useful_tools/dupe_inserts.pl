#!/usr/bin/env perl

while (<>) {
  if (/Duplicate entry .(\d+).* INSERT INTO .(\w+)/) {
    $x{$2}{$1}++;
  }
}

while (($table, $ids) = each %x) {
  foreach (sort {$ids->{$b} <=> $ids->{$a}} grep {$ids->{$_} > 0} keys %$ids) {
    print "$table: $_: $ids->{$_}\n";
  }
}
