# Correr composer install
 
La app inicia desde index.php. En el campo de texto se realiza la consulta. En este caso decidi utilizar BING como buscador
Hay que buscar la lista con la clase "b_algo" que es donde lista los resultados. 
Con el paquete Goutte filtramos la request y buscamos por esa clase y luego obtenemos las URL de donde sacamos los dominios.
Luego se almacenan los dominios en una tabla de una base de datos MYSQL la cual se conecta desde db.php con una clase.
El resultado es mostrado en una tabla y para la UI en general del sitio para realizar algo rapido se utilizo bootstrap desde CDN.






# Respuestas al ejercicio numero 2 de base de datos.


1)

select roles.rol,count(*) as total 
from usuarios 
INNER join roles on usuarios.rol_id = roles.id
GROUP BY roles.id 
ORDER BY total DESC;

2)

select usuarios.*
FROM usuarios
where usuarios.id not in (select distinct usuario_id as id from feedback)
and usuarios.id in (select distinct usuario_id as id from soporte_act)

3)

DELETE from ratones 
where (marca in (select distinct marca from teclado) OR (select distinct marca from monitores))