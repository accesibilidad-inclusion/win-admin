# Preguntas sobre estructura de datos y reglas de negocio

* Describir roles de Usuario: usuario, investigador, admin (a qué cosa puede acceder o qué acciones puede realizar cada tipo de usuario)
* ¿Un usuario sólo puede contestar 1 vez el cuestionario?
* ¿Las "dimensiones" de una pregunta son entidades separadas de los "indicadores" o sólo corresponden a una forma de agrupar los indicadores? Si son entidades separadas, ¿en qué se diferencian?
* Una pregunta, ¿siempre tiene 3 respuestas "sí" y 3 respuestas "no"?
* El orden de las respuestas, ¿debe ser editable?
* Las alternativas de "especificación", ¿siempre son "En el hogar", "Fuera del Hogar" y "Siempre"?
* ¿A qué corresponden las siguientes entidades presentes en la base de datos: "amigos", "token_amistad", "trabajo_investigador"?
* ¿A qué objetos se asocian las "region", "provincia" y "comuna" existentes en la base de datos?
* ¿Qué datos se deben recolectar de los usuarios que contestan el cuestionario?
* ¿Cuál es el objetivo de la integración con Facebook en la app de cuestionario?

---

# Questions ("preguntas")

## Esquema

* id
* enunciado - Enunciado: string
* necesita_especificacion - Necesita especificación: bool
* especificacion - Especificación: string
* orden: smallint

## Relaciones

* hasOne Pregunta_Dimension
* hasOne Pregunta_Categoria
* hasMany Pregunta_Area_Apoyo
* hasMany Pregunta_Respuesta

---

# Dimension

# Esquema

* id
* label

## Relaciones

* belongsToMany Pregunta

## Elementos (→ Indicadores)

* Autonomía
 + Realización de Elecciones
 + Toma de Decisiones
 + Resolución de Problemas
* Autorregulación
 + Establecimiento de Metas
 + Autoinstrucción
 + Autoevaluación
* Creencias de Control-Eficacia
 + Autodefensa
 + Locus de Control Interno
 + Expectativas de Logro
 + Atribuciones de Eficacia
* Autorrealización
 + Autoconocimiento

---

# Category (categoría)

## Esquema

* id
* label

## Pivot: pregunta_categoria

* pregunta_id
* categoria_id

## Relaciones

* belongsToMany Pregunta

## Elementos

* Voluntad
* Competencia
* Oportunidad
* Apoyo

---

# assistance (Área de Apoyo)

## Elementos

* Desarrollo humano
* Enseñanza y educación
* Vida en el hogar
* Vida en comunidad
* Empleo
* Salud y seguridad
* Actividades conductuales
* Actividades sociales
* Protección y defensa

---

# option (Opciones)

## Esquema

* pregunta_tipo: enum [si, no]
* label: varchar
* order: tinyint

## Relaciones
* belongsTo Pregunta

---

# Respuestas

## Esquema

* id (bigint)
* pregunta_id
* value
* start_time/end_time || duration || duration_sec // response_time (?)

---

# Apoyos

* respuesta_id
* apoyo_id

# Especificaciones

* En el hogar, fuera del hogar, siempre