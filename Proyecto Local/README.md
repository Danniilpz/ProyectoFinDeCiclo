
Para ejecutar el proyecto en un servidor local, se debe situar la carpeta "Proyecto" en la raiz del servidor

La base de datos se puede cargar de dos formas:

	- base_de_datos.sql: de esta forma la bbdd ya tendrá el usuario administrador creado, y ya tendrá sitios indexados.
		Usuario administrador: daniel.lopez@loopz.cf
		Contraseña: cifpponferrada
	- script.sql + ejecutar "php artisan migrate" dentro de la carpeta: de esta forma el primer usuario creado será 
		el administrador, y la lista de sitios estará inicialmente vacía.

El programa Bot.jar situado en esta carpeta apunta al servidor local. 
	base de datos-->localhost
	robot_status-->http://127.0.0.1/Proyecto/robot_status
	