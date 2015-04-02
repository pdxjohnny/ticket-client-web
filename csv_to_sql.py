#!/usr/bin/env python

import sys, os, csv, sqlite3

def csv_to_sql( file_name, column_names, table_name ):
	with open( file_name, 'rb' ) as file_input:
		csv_file = [ tuple(line) for line in csv.reader(file_input) ]
		db = csv_file[1:]
		headers = csv_file[0]
		existing_columns = list(headers)
		for new in column_names:
			if not new in existing_columns:
				existing_columns.append(new)
		column_names = existing_columns

	con = sqlite3.connect( '.tickets.db' )
	cur = con.cursor()
	query = "CREATE TABLE IF NOT EXISTS `" + table_name + "` (`" + '`, `'.join(column_names) + "`)"
	print query
	cur.execute( query )
	cur.execute( "DELETE FROM `" + table_name + "`" )
	query = "INSERT OR REPLACE INTO `" + table_name + "` (`" + '`, `'.join(headers) + "`) VALUES ( " + '?, '*(len(headers) - 1) + "? );"
	print query
	cur.executemany( query, db )
	con.commit()

if __name__ == "__main__":
	csv_to_sql( sys.argv[1], [], sys.argv[2] )
	os.remove( sys.argv[1] )

