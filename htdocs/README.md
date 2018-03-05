# Preguntas sobre estructura de datos y reglas de negocio

---

# Modelos

## Sujeto

* id
* Doc. identidad
* Nombre
* Apellidos
* Sexo
* Fecha nacimiento
* datetime consentimiento → nullable
* geolocalización
* institución
* última conexión
* Discapacidad → json(?)
  + cognitiva
  + visual
  + auditiva
  + otra
* ocupación → json(?)
  + Trabaja?
  + Dónde Trabaja
  + Estudia?
  + Dónde estudia
---
* id rrss
* red rrss
* token auth rrss

## Aplicación

* id
* subject_id
* evento_id
* hash (identificador del dispositivo) → unique
* created_at
* updated_at

## Evento

* Nombre
* Fecha de inicio
* Fecha de término(?)
* Estado
* Hash
* → Institución asociada
* → Creador (investigador)

## Institución

* Nombre
* → Eventos

## Roles de usuario

> Describen agrupaciones de permisos

### Investigador

* Puede ver todos los resultados
* Crea eventos y cerrar eventos
* Puede crear instituciones

### Director

* Crea usuarios en la institución
* Puede ver todos los resultados de su institución
* Está asociado a una o más instituciones
* Sólo puede ver los que están asociados a sus aplicaciones (no los de la institución)
* No puede exportar datos

### Profesor

* Puede ver los resultados de su nivel
* Está asociado a una o más instituciones

### Aplicador

* Se asocia a un evento, pero no tiene permisos de lectura
* No está asociado a una institución
* aplicador_evento Many To Many

## Permisos

## Eventos

> Describen a una aplicación guiada y controlada del cuestionario.
> Un "evento" se relaciona a una o más instituciones, a través de las cuales se delegan permisos a directores, profesores y aplicadores

## Instituciones

> Colegios u otras organizaciones donde se realiza un evento

## Guiones

> Describen los guiones para la aplicación del cuestionario, ya sea asociado a un evento o de forma autónoma
> Inicialmente existe solo un guión único, determinado por el orden de las preguntas