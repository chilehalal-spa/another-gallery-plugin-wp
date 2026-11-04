# Another Gallery Plugin

Plugin modular de galerías con estructura assets/includes.

Se usa en: chilehalal.cl, chisi.cl.

## Instalación

1. Descarga `another-gallery-plugin.zip` desde la
   [última release](https://github.com/chilehalal-spa/another-gallery-plugin-wp/releases/latest).
   No uses el botón "Code → Download ZIP": ese zip trae otro nombre de carpeta.
2. En WordPress: *Plugins → Añadir nuevo → Subir plugin*, elige el zip y activa.

## Uso

Shortcodes: `[another_gallery_card]`, `[another_gallery_view]`.

## Actualizaciones

El plugin se actualiza desde las releases de este repositorio, como cualquier
plugin de wordpress.org: aparecen en *Escritorio → Actualizaciones* y se pueden
dejar automáticas en *Plugins → Activar actualizaciones automáticas*.

WordPress revisa cada 12 horas; la respuesta de GitHub se guarda 6 horas.

## Publicar una versión nueva

1. Sube `Version:` en `another-gallery-plugin.php` (por ejemplo `2.2.0`).
2. Haz commit y push.
3. Crea y sube el tag con el mismo número:

   ```bash
   git tag v2.2.0
   git push origin v2.2.0
   ```

La Action [Release](.github/workflows/release.yml) verifica que el tag coincida
con la versión, arma `another-gallery-plugin.zip` y publica la release. Desde ese
momento los sitios ven la actualización.

## Licencia

GPL-2.0-or-later. Ver [LICENSE](LICENSE).
