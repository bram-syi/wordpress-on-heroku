import time
import calendar
import re
import sys
import tempfile
import optparse

# if do_cleanup is True:
#   return string, which is the timestamp converted into a fixed format
#   in local time
# else:
#   return float, number of seconds since epoch of the timestamp
def get_time(line, do_cleanup=False):
    m = re.search(r'^(?:\S+ - - )?\[(.*?)\]', line) # apache
    if not m:
        m = re.search(r'^\[?(\d\d\d\d-\d\d-\d\dT\d\d:\d\d:\d\d\+00:00)\]?', line) # other logs
        if not m:
            m = re.search(r'^\S+ \[(.*?)\]', line) # consolidate log from SyiLog class
            if not m:
                if do_cleanup:
                    return line
                else:
                    return 0

    stamp = original_stamp = m.group(1)

    stamp = re.sub(r'\s+(UTC|[-+]\d\d\d\d)$', '', stamp)

    formats = [
        ("%d-%b-%Y %H:%M:%S", True),        # php log (14-Dec-2012 01:04:27), UTC=True
        ("%a %b %d %H:%M:%S %Y", False),    # apache error log (Thu Dec 13 17:02:41 2012), UTC=False
        ("%d/%b/%Y:%H:%M:%S", False),       # apache access log (13/Dec/2012:17:05:20 -0800), UTC=False
        ("%Y-%m-%dT%H:%M:%S+00:00", True)   # facebook/txn log (2012-12-20T00:27:53+00:00), UTC=True
    ]

    for f in formats:
        try:
            t_struct = time.strptime(stamp, f[0])
            if f[1]:
                epoch_seconds = calendar.timegm(t_struct)
            else:
                epoch_seconds = time.mktime(t_struct)

            if do_cleanup:
                local_struct = time.localtime(epoch_seconds)
                clean_stamp = time.strftime("%a %d %b %H:%M:%S", local_struct)
                removed_stamp = re.sub(r'\S*' + re.escape(original_stamp) + r'\S*\s*', '', line)
                return '[' + clean_stamp + '] ' + removed_stamp
            else:
                return epoch_seconds

        except ValueError:
            continue

    raise Exception('Unable to parse timestamp: ' + stamp)

def shorten_line(line):
    global cols
    if not 'cols' in globals():
        try:
            # http://blog.taz.net.au/2012/04/09/getting-the-terminal-size-in-python/
            import fcntl, termios, struct
            hw = struct.unpack('hh', fcntl.ioctl(1, termios.TIOCGWINSZ, '1234'))
            cols = str(hw[1])
        except:
            cols = '80'

    line = re.sub(r'\s*\n\s*', ' ', line)
    return re.sub('^(?P<first>.{' + cols + '}).*', '\g<first>', line)

def short_lines_option(parser):
    return parser.add_option('--short-lines', '-s', action="store_true",
        help="Make lines shorter: remove newlines, and truncate to term width")

# given a cookie filename and a url, return the value for an HTTP cookie
# header
# use this Chrome ext:
# https://chrome.google.com/webstore/detail/cookietxt-export/lopabhfecdfhgogdbojmaicoicjekelh
def cookieFromFile(filename, url):
    import cookielib, urllib2, tempfile, os, codecs

    # cookielib won't even look at the rest of a cookie file if the first line
    # doesn't match a particular regex, so we always guarantee the magic_re
    # will succeed
    real = codecs.open(filename, "r", 'utf8')
    tmp = tempfile.NamedTemporaryFile(delete=False)
    tmp.write("# Netscape HTTP Cookie File\n")
    tmp.write(real.read())
    tmp.close()

    # we only need this Request() long enough to have cookielib set the Cookie
    # header for us, we never actually pass it urlopen() or the like
    req = urllib2.Request(url)
    jar = cookielib.MozillaCookieJar(tmp.name)
    jar.load()
    jar.add_cookie_header(req)

    cookie = req.get_header("Cookie")
    if not cookie:
        cookie = ""

    os.unlink(tmp.name)
    return cookie

# This function takes an html file, and finds all table rows. Each row is then
# split to its cells, and the cells are output as both csv and sql inserts.
#
# filename: filename of an html file ("-" for stdin)
# table: the table name we are inserting into (for sql)
# sql_fixup: function that takes a list of <td>, and returns a fixed-up list
#   of strings that can be used in sql
def html_to_fields(filename, table='temp', sql_fixup=False):
    from bs4 import BeautifulSoup, SoupStrainer
    import csv, cStringIO, codecs

    # http://docs.python.org/2/library/csv.html
    class UnicodeWriter:
        def __init__(self, f, dialect=csv.excel, encoding="utf8", **kwds):
            # Redirect output to a queue
            self.queue = cStringIO.StringIO()
            self.writer = csv.writer(self.queue, dialect=dialect, **kwds)
            self.stream = f
            self.encoder = codecs.getincrementalencoder(encoding)()

        def writerow(self, row):
            self.writer.writerow([s.encode("utf8") for s in row])
            # Fetch UTF-8 output from the queue ...
            data = self.queue.getvalue()
            data = data.decode("utf8")
            # ... and reencode it into the target encoding
            data = self.encoder.encode(data)
            # write to the target stream
            self.stream.write(data)
            # empty queue
            self.queue.truncate(0)

    # call writerow() with a list of columns. Columns will be serialized into
    # rows, suitable for use in a sql insert like so:
    #   INSERT INTO a VALUES (row1), (row2), (row3)...
    class SqlWriter:
        # fh: a file object
        # table: the name of the table to insert into
        def __init__(self, fh, table):
            self.out = fh
            self.table = table
            self.buffer = []
            self.limit = 500

        # cols: list of columns
        def writerow(self, cols):
            cols = [re.sub(r'"', '\\"', x) for x in cols]
            cols = ['"' + re.sub(r'\\+"', '\\"', x) + '"' for x in cols]
            if cols[17] == '""':
                cols[17] = 'null'
            if cols[18] == '""':
                cols[18] == 'null'
            cols.append('"new"')
            cols.append('null')
            self.buffer.append('(' + ','.join(cols) + ')')
            if len(self.buffer) > self.limit:
                self._flush_buffer()

        def _flush_buffer(self):
            if len(self.buffer):
                s = ',\n'.join(self.buffer)
                self.out.write("INSERT INTO " + self.table + ' VALUES ' + s + ';\n')
                self.buffer = []

        def _extra_sql(self):
            extra_sql = """
update payment p
left join donation_report dr on dr.payment_id=p.id and dr.type2='SPEND GC'
set p.gc_amount = -dr.card;
"""
            self.out.write(extra_sql)

        def __del__(self):
            self._flush_buffer()
            self._extra_sql()

    if (filename == '-'):
        fh = sys.stdin
        tmp = tempfile.NamedTemporaryFile(delete=False)
        outfile = tmp.name
    else:
        fh = codecs.open(filename, 'r', 'utf8')
        outfile = re.sub(r'\.html?$', '', filename, flags=re.IGNORECASE)

    for ext in ['csv', 'sql']:
        print "parse: writing to %s.%s" % (outfile, ext)

    csv_out = UnicodeWriter(open(outfile + '.csv', 'w+b'))
    sql_out = SqlWriter(codecs.open(outfile + '.sql', 'w', 'utf8'), table)

    soup = BeautifulSoup(fh, 'html.parser')
    rows = 0
    for row in soup.find_all('tr'):
        rows += 1
        sys.stdout.write('\rrow: ' + str(rows))

        cells = [ x.get_text() for x in row.find_all('td') ]
        if len(cells) == 0:
            cells = [ x.get_text() for x in row.find_all('th') ]
            if len(cells) > 0:
                sys.stdout.write("\nparse: found " + str(len(cells)) + " headers to print\n")
                csv_out.writerow(cells)
                # sql doesn't get anything on <th> rows
        else:
            csv_out.writerow(cells)
            if sql_fixup:
                cells = sql_fixup(cells)
            if len(cells):
                sql_out.writerow(cells)

    print "\nparse: found " + str(rows) + " total rows"

def db(env):
    import MySQLdb
    return MySQLdb.connect('mysql.seeyourimpact.com', 'syidb', 'nischal1999', 'impactdb_'+env)
