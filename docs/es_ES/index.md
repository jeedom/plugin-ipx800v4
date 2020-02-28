Este complemento le permite administrar un IPX 800 v4

Configuración del complemento 
=======================

Después de descargar el complemento, solo necesita activarlo y marcar las casillas que le interesan para las solicitudes de IPX800v4 (por ejemplo, marque X-Dimmer si tiene un X-dimmer). También puede elegir la frecuencia de las solicitudes para aligerar la carga en ipx800v4

> **importante**
>
> Después de cada modificación de la frecuencia o solicitudes, debe reiniciar el daemon para que se tenga en cuenta

> **importante**
>
> Para aligerar la carga, se recomienda desmarcar TODAS las solicitudes de tipo y simplemente verificar las API que le conciernen (encontrará detalles en la documentación de la API para IPX800v4)

> **nota**
>
> Puede ver en esta página el estado del demonio que monitorea el IPX800

Configuración del equipo 
=============================

Se puede acceder a la configuración de los dispositivos IPX800 desde el menú
plugin luego protocolo de automatización del hogar :

Aquí encontrarás toda la configuración de tu equipo :

-   **Nombre del equipo IPX800** : nombre de su equipo IPX800,

-   **Objeto padre** : indica el objeto padre al que pertenece
    equipos,

-   **Activar** : activa su equipo,

-   **visible** : hace que su equipo sea visible en el tablero de instrumentos,

-   **categoría** : categorías de equipos (puede pertenecer a
    varias categorías),

-   **IP** : La ip IPX800,

-   **Clave API** : Clave API IPX800 (por defecto la clave API
    es apikey)

El complemento predeterminado no crea nada, dependerá de usted hacerlo
dependiendo de tu configuración pero serás guía.

orden 
========

acción 
------

Tienes 3 tipos de acción :

-   **uno** : le permite establecer una salida (o entrada virtual) a 1, o
    asignar un valor a una entrada analógica (o contador)

-   **apagado** : le permite establecer una salida (o entrada virtual) a 0

-   **mecedora** : permite invertir el estado de una salida (o
    entrada virtual)

Tienes 6 tipos de actuadores :

-   **retransmitir**

-   **Salida virtual**

-   **Entrada virtual**

-   **Entrada analógica virtual**

-   **contador**

-   **Cable piloto**

> **nota**
>
> Cierto tipo de actuador puede ocultarse dependiendo del tipo
> acción

Luego, dependiendo del tipo de acción y el actuador que tenga
varios parámetros que pueden ser :

-   número de relevo

-   número de salida virtual

-   número de entrada virtual

-   número de entrada analógica y valor para asignar (dejar en blanco si
    quieres elegir con el cursor)

-   Número de contador y operación (ex +200 o -100)

-   número de cable piloto y valor de pedido (0 confort, 1 eco, 2 sin escarcha, 3 apagado, 4 confort-1, 5 confort-2)

información 
----

Tienes 11 tipos diferentes :

-   retransmitir

-   Entrada digital

-   Entrada virtual

-   Salida virtual

-   perro guardián

-   EnOcean

-   Entrada analógica

-   Entrada analógica virtual

-   contador

-   Persiana enrollable : opción en el formulario 1-3 para la parte 3 de
    extensión VR 1

-   THL : en forma 1-TEMP para la temperatura del sensor 1, 3-HUM
    para sensor de humedad 3 o 2-LUM para brillo del sensor
    2

Para cada tipo, jeedom le preguntará el número de la información deseada.

plantilla 
========

Para ayudarlo, hay una plantilla que le permite crear ciertos tipos de
ordenar de una vez y más rápido.