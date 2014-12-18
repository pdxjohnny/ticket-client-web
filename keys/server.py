import SimpleHTTPSServer
import json
import sqlite3
import csv
import ConfigParser
import datetime
from jinja2 import Environment, PackageLoader

class db(object):
	"""docstring for db"""
	def __init__(self, file_name):
		super(db, self).__init__()
		self.file_name = file_name
		self.con = sqlite3.connect(self.file_name, isolation_level=None)

	def query( self, query ):
		with self.con:
			cur = self.con.cursor()
			cur.execute( query )

	def one( self, query ):
		with self.con:
			cur = self.con.cursor()
			cur.execute( query )
			data = cur.fetchone()
		return data

	def all( self, query ):
		with self.con:
			cur = self.con.cursor()
			cur.execute( query )
			data = cur.fetchall()
		return data

class server(SimpleHTTPSServer.handler):
	"""docstring for server"""
	def __init__( self ):
		super(server, self).__init__()
		self.actions = [ ( 'post', '/', self.post_echo ),
			( 'get', '/posts/:date', self.get_posts ),
			( 'get', '/post/:id', self.get_post ),
			( 'get', '/', self.index ) ]
		self.db = db('blog.db')
		self.templates = Environment(loader=PackageLoader('blog', 'templates'))
		self.config = ConfigParser.RawConfigParser()
		self.config.read('config.ini')
		self.props = {
			'blog_name': self.config.get('blog', 'name')
		}
		
	def post_echo( self, request ):
		form_data = self.form_data( request['data'] )
		output = json.dumps(form_data)
		headers = self.create_header()
		headers = self.add_header( headers, ( "Content-Type", "application/json") )
		return self.end_response( headers, output )

	def get_echo( self, request ):
		pass

	def get_posts( self, request ):
		if request['variables']['date'].count('/') >= 3:
			request['variables']['date'] = datetime.datetime.strptime(request['variables']['date'], "%Y/%m/%d")
			request['variables']['date'] = request['variables']['date'].strftime("%Y %B %d")
		elif request['variables']['date'].count('/') == 1:
			request['variables']['date'] = datetime.datetime.strptime(request['variables']['date'], "%Y/%m")
			request['variables']['date'] = request['variables']['date'].strftime("%Y %B")
		request['variables'].update( self.props )
		output = self.templates.get_template('posts.html').render( request['variables'] )
		headers = self.create_header()
		return self.end_response( headers, output )

	def get_post( self, request ):
		request['variables'].update( self.props )
		output = self.templates.get_template('post.html').render( request['variables'] )
		headers = self.create_header()
		return self.end_response( headers, output )

	def index( self, request ):
		output = self.templates.get_template('index.html').render( self.props )
		headers = self.create_header()
		return self.end_response( headers, output )


def main():
	address = "0.0.0.0"
	port = 80

	run_server = SimpleHTTPSServer.server( ( address, port ), server(), threading = True )

if __name__ == '__main__':
	main()
