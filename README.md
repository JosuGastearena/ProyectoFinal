## Miembros del grupo

- Josu Gastearena salgado
- Ainhoa Quintana Beraiz
- Ander Flores Palacios
- Aimar Uriarte Burdaspar

## Metodología de trabajo y Organización

Comenzamos realizando la práctica final todos juntos en las vacaciones de Semana Santa, conectandonos a traves de Discord y trabajando los 4 en un único ordenador. De esta manera, empezamos a definir la manera en la que íbamos a abordar el trabajo y a entender un poco como debía ser resuelto, comparándolo con la práctica anterior: user-basic-api.

Al volver las clases, y tras las diferentes explicaciones, decidimos dividir el trabajo y realizar los endPoint por parejas: Ander y Ainhoa por un lado, y Josu y Aimar por otro. También tomamos la decisión de no dividir el trabajo a realizar en cada endpoint ya que queríamos aprender a realizar un endpoint de principio a fin.

Finalmente, esta última semana, hemos vuelto a trabajar igual que lo hicimos en Semana Santa, mayoritariamente desde el ordenador de Ander por problemas en la configuración de la cache de los otros integrantes del grupo, que finalmente fueron resueltos.

## Métodologia de resolución de un endpoint

Para resolver la práctica hemos realizado TDD, es decir, hemos guiado el diseño de nuestro código a traves de tests. En primer lugar, escribiamos los tests para el controlador pertinente, tests que obviamente fallaban, e íbamos implementando el código necesario hasta conseguir que el test pasara, para finalmente refactorizar y refinar la solución.

## Objetivos cumplidos

Hemos conseguido implementar todos los endpoint que se pedian en la práctica. Trás haber conseguido los objetivos, hemos dedicado un tiempo a refinar el código, aplicando diferentes técnicas como el refactor: rename y el CS Fixer, para conseguir un código más mantenible.

Finalmente, el último día hemos sacado fuera la dependencia de la API en una clase Client, para poder testar la clase CryptoCurrenciesDataSource; no obstante, hemos tenido problemas y no hemos conseguido testar el método coinStatus, por problemas con los tipos del response.

## Cosas a tener en cuenta

Hemos intentado aplicar las técnicas aprendidas en clase para un buen diseño software, y aunque no hemos contado con el tiempo necesario para poder aplicarlas de la mejor manera posible, esperamos que se vean reflejadas en los 6 endpoints.

Finalmente, en github actions hemos automatizado la ejecución de los tests y al hacer commit también con grumphp hemos ejecutado los tests para comprobar que eran correctos antes de commit-ear un cambio. Hemos trabajado también con Pull-Request.
