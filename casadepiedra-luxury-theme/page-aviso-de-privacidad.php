<?php
/**
 * Template Name: Aviso de Privacidad
 * Descripción: Página oficial del Aviso de Privacidad para Casa de Piedra / Grupo AlCon.
 */

get_header();
?>

<style>
/* Estilos Exclusivos para Aviso de Privacidad */
.privacy-page-wrapper {
    background-color: var(--color-bg);
    color: var(--color-text);
    padding-top: calc(var(--header-height, 80px) + 3rem);
    padding-bottom: 6rem;
    min-height: 100vh;
}

.privacy-hero {
    text-align: center;
    max-width: 900px;
    margin: 0 auto 4rem auto;
    padding: 0 1.5rem;
}

.privacy-hero .script-subtitle {
    font-family: var(--font-script, 'Great Vibes', cursive);
    font-size: clamp(1.8rem, 4vw, 2.5rem);
    color: var(--color-accent);
    margin-bottom: 0.5rem;
    display: block;
}

.privacy-hero h1 {
    font-family: var(--font-heading);
    font-size: clamp(2.2rem, 5vw, 3.5rem);
    color: #fff;
    margin: 0 0 1.5rem 0;
    font-weight: 400;
    letter-spacing: 1px;
}

.privacy-divider {
    width: 80px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--color-accent), transparent);
    margin: 0 auto;
}

.privacy-container {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.privacy-intro-box {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 16px;
    padding: 2rem 2.5rem;
    margin-bottom: 3rem;
    font-size: 1.05rem;
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.9);
}

.privacy-section {
    background: rgba(18, 18, 18, 0.75);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 16px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    transition: border-color 0.3s ease;
}

.privacy-section:hover {
    border-color: rgba(212, 175, 55, 0.25);
}

.privacy-section h2 {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    color: var(--color-accent);
    margin: 0 0 1.25rem 0;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.privacy-section h2::before {
    content: "◈";
    font-size: 1.1rem;
    color: var(--color-accent);
}

.privacy-section p {
    color: rgba(255, 255, 255, 0.82);
    line-height: 1.8;
    font-size: 1rem;
    margin-bottom: 1.25rem;
}

.privacy-section p:last-child {
    margin-bottom: 0;
}

.privacy-list {
    list-style: none;
    padding: 0;
    margin: 1.25rem 0;
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.85rem;
}

.privacy-list li {
    position: relative;
    padding-left: 1.75rem;
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.6;
}

.privacy-list li::before {
    content: "•";
    position: absolute;
    left: 0.5rem;
    color: var(--color-accent);
    font-size: 1.25rem;
    line-height: 1.3;
}

.privacy-contact-card {
    background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(15, 15, 15, 0.9) 100%);
    border: 1px solid var(--color-accent);
    border-radius: 16px;
    padding: 2.5rem;
    margin-top: 3rem;
}

.privacy-contact-card h3 {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    color: #fff;
    margin: 0 0 1.5rem 0;
}

.privacy-contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
}

.privacy-contact-item label {
    display: block;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--color-accent);
    margin-bottom: 0.35rem;
}

.privacy-contact-item span,
.privacy-contact-item a {
    color: #fff;
    font-size: 0.95rem;
    line-height: 1.5;
    text-decoration: none;
}

.privacy-contact-item a:hover {
    color: var(--color-accent);
    text-decoration: underline;
}

@media (max-width: 768px) {
    .privacy-intro-box,
    .privacy-section,
    .privacy-contact-card {
        padding: 1.5rem;
    }
}
</style>

<div class="privacy-page-wrapper">
    <div class="privacy-hero">
        <span class="script-subtitle">Marco Legal y Confidencialidad</span>
        <h1>Aviso de Privacidad</h1>
        <div class="privacy-divider"></div>
    </div>

    <div class="privacy-container">
        <!-- Introducción & Grupo AlCon -->
        <div class="privacy-intro-box">
            Entre las empresas filiales y/o subsidiarias pertenecientes a <strong>Grupo AlCon</strong> se pueden encontrar: <em>Inmobiliaria Granjas de la Loma SAPI de C.V., Grupo Inmobiliario Granjas de León S.A. de C.V., Administradora y Operadora de CC de León S.A. de C.V., Asociación de Condóminos Centro Comercial Plaza Mayor A.C., Servicios Corporativos Plaza Mayor S.A. de C.V., Arlomar, S.A. de C.V., Operadora Cerro Gordo S.A. de C.V., Impulsora Mexicana de Servicios S.A. de C.V., y Desarrolladora la Mezquitera S.A. de C.V.</em>
        </div>

        <!-- 1. Importancia -->
        <div class="privacy-section">
            <h2>Importancia del tratamiento de sus datos personales</h2>
            <p>
                <strong>Grupo AlCon</strong> y todo su personal reconoce la importancia del adecuado tratamiento de sus datos personales y, como consecuencia de ello, ha implementado diversos controles y medidas de seguridad administrativas, técnicas y físicas orientados a que dicha información conserve el carácter de confidencial y a prevenir un daño, pérdida, alteración, destrucción, uso indebido, acceso y/o tratamiento no autorizados, de conformidad con lo dispuesto en la <strong>Ley Federal de Protección de Datos Personales en Posesión de Particulares</strong> (en lo sucesivo: <em>LFPDPPP</em>), y demás legislación aplicable en la materia (en adelante: la <em>Ley</em>).
            </p>
        </div>

        <!-- 2. Responsable -->
        <div class="privacy-section">
            <h2>Responsable del tratamiento</h2>
            <p>
                El responsable de recabar sus datos personales, del uso que se les dé y de la protección de los mismos es: <strong>Inmobiliaria Granjas de la Loma SAPI de CV</strong> (en lo sucesivo: <strong>IGL</strong>), con domicilio ubicado en <strong>Blvd. Juan Alonso de Torres # 2002 int. 3001, Col. Valle del Campestre, C.P. 37125, en la ciudad de León, Guanajuato</strong>.
            </p>
        </div>

        <!-- 3. Cómo obtenemos sus datos personales -->
        <div class="privacy-section">
            <h2>Cómo obtenemos sus datos personales y Uso de Cookies / Rastreo</h2>
            <p>
                Podemos conseguir información sobre usted a través de diversas fuentes, como: cuando la obtenemos de forma directa, cuando visita nuestros sitios, cuando utiliza nuestros servicios o redes sociales en línea y por último, cuando la recopilamos de otros orígenes que permite la Ley.
            </p>
            <p>
                Para asegurar la buena administración de nuestros sitios web y facilitar una mejor navegación dentro de los mismos, se pueden utilizar <strong>«cookies»</strong> (breves archivos de texto almacenados en el navegador del usuario) o <strong>«web beacons»</strong> (imágenes electrónicas que permiten contar el número de visitantes y usuarios que han ingresado a un sitio web) para almacenar, agregar y reportar información, así como herramientas analíticas y de rastreo tecnológico proporcionadas por <strong>Google Analytics</strong> y servicios conexos de Google.
            </p>
            <p>
                <strong>IGL</strong> puede usar estas herramientas como fuente de información útil y efectiva para rastrear información e identificar categorías de usuarios como nombres, direcciones IP, dominio, tipo de navegador y páginas visitadas. Sin embargo, no recaba: números de cuenta, contraseñas, códigos de seguridad bancarios u otros similares.
            </p>
            <p>
                En circunstancias específicas, el acceso puede ser denegado en ciertas partes de nuestros sitios a aquellos visitantes y usuarios cuyos navegadores no permitan el uso de «cookies». Usted puede deshabilitar estas tecnologías siguiendo los medios que indica el navegador de internet que utiliza.
            </p>
            <p>
                Por motivos de seguridad, <strong>Grupo AlCon</strong> dispone equipos de <strong>video-vigilancia</strong> en sus instalaciones, capaces de recopilar imágenes, fotografías, videos, voces y/o sonidos, orientados a hacer identificable a cualquier individuo que se encuentre o transite por ellas.
            </p>
        </div>

        <!-- 4. Datos personales recabados -->
        <div class="privacy-section">
            <h2>Datos personales que son recabados</h2>
            <p>Entre la información que podemos recopilar de usted, se encuentra:</p>
            <ul class="privacy-list">
                <li><strong>Identificación y Demografía:</strong> Nombre, lugar y fecha de nacimiento, domicilio, nacionalidad e información demográfica, como: estado civil y número de familiares o dependientes económicos.</li>
                <li><strong>Identificadores Gubernamentales:</strong> Notaciones de identificación personal, como: Clave Única de Registro de Población (CURP), Registro Federal de Contribuyentes (RFC) y/o número de afiliación al Instituto Mexicano del Seguro Social (IMSS).</li>
                <li><strong>Contacto y Referencias:</strong> Detalle de contacto y/o referencia: vínculo, números telefónicos o correos electrónicos.</li>
                <li><strong>Reseña Académica/Profesional:</strong> Reseña académica y/o profesional.</li>
                <li><strong>Situación Patrimonial y Financiera:</strong> Situación patrimonial y/o financiera, con: relación de cuentas, instituciones bancarias e historial crediticio.</li>
                <li><strong>Otros:</strong> Cualquier otra de naturaleza análoga que nos permita operar y cumplir con nuestras obligaciones comerciales, administrativas y laborales.</li>
            </ul>
            <p>
                <em>Grupo AlCon no solicita a sus clientes datos personales sensibles que puedan revelar aspectos como origen racial o étnico, estado de salud actual y futura, información genética, creencias religiosas, filosóficas y morales, afiliación sindical, opiniones políticas o preferencia sexual.</em>
            </p>
        </div>

        <!-- 5. Finalidades -->
        <div class="privacy-section">
            <h2>Finalidades del tratamiento</h2>
            <p>Usamos los datos recogidos para brindarle los productos y servicios que solicita, para mantenerlo informado y poder administrar nuestros portales, primordialmente con las siguientes finalidades:</p>
            <ul class="privacy-list">
                <li>Integrar nuestro padrón de clientes, prospectos y/o contactos.</li>
                <li>Elaborar y celebrar contratos de: arrendamiento, eventos o prestación de servicios.</li>
                <li>Proveer los servicios o productos contratados.</li>
                <li>Exigir el cumplimiento de las partes a las obligaciones contractuales.</li>
                <li>Informar sobre modificaciones o condiciones relacionados con un contrato adquirido, así como sobre otros productos y servicios que ofrece Grupo AlCon.</li>
                <li>Elaborar estudios de mercadotecnia, segmentación, estadísticas y calidad.</li>
                <li>Atender sus dudas, quejas y sugerencias.</li>
                <li>Invitarlo a participar en nuestros concursos, sorteos o actividades en redes sociales.</li>
                <li>Constituir parte activa en nuestros programas informativos, comerciales o promocionales.</li>
            </ul>
        </div>

        <!-- 6. Transferencia de datos -->
        <div class="privacy-section">
            <h2>Transferencia de datos</h2>
            <p>
                <strong>Grupo AlCon</strong> podrá transferir sus datos personales a terceros nacionales o extranjeros, así como a nuestras empresas filiales y/o subsidiarias, con la obligación de comunicar a éstos el contenido de este Aviso de Privacidad y en el entendido de que el tercero receptor asumirá las mismas obligaciones que correspondan a IGL. Así mismo, cuando le sean requeridos por las autoridades en cumplimiento de la LFPDPPP, esto último, sin la necesidad de tener el consentimiento del titular.
            </p>
        </div>

        <!-- 7. Aceptación del tratamiento -->
        <div class="privacy-section">
            <h2>Aceptación del tratamiento</h2>
            <p>
                El hecho de proporcionar sus datos personales de manera directa o indirecta, al completar los formatos impresos, electrónicos y demás medios que Grupo AlCon dispone, o continuar navegando dentro de nuestro sitio web, supone su aceptación íntegra y expresa del presente Aviso de Privacidad así como de cualquier modificación y/o actualización del mismo.
            </p>
        </div>

        <!-- 8. Procedimiento ARCO -->
        <div class="privacy-section">
            <h2>Procedimiento para el ejercicio de los derechos ARCO, Revocación o Restricción</h2>
            <p>
                Usted o su representante legal, en su caso, están en posibilidad de ejercer cualquiera de los derechos de Acceso, Rectificación, Cancelación y Oposición (en adelante: <strong>ARCO</strong>), así como de revocar el consentimiento para el tratamiento o transferencia de sus datos personales, para lo cual deberá solicitar un formato de <em>“Solicitud para ejercer Derechos de Acceso, Rectificación, Cancelación y Oposición (ARCO), Revocación del Consentimiento o Restricción de Transferencia”</em> (en adelante: la Solicitud), ya sea de manera directa o mediante correo electrónico al Departamento de Datos Personales de IGL.
            </p>
            <p>
                La presentación formal de su gestión inicia mediante la presentación o envío de la Solicitud debidamente contestada, con su información correcta y completa debe presentarse o remitirse a la dirección electrónica del Departamento, que la revisará y determinará si cuenta con los elementos necesarios para atender su gestión.
            </p>
            <p>
                Es requisito que usted señale en la Solicitud el documento con cuya copia le acompañará para acreditar su identidad, como: Credencial del Instituto Federal Electoral / INE, Pasaporte vigente, Cédula Profesional, Cartilla del Servicio Militar Nacional, Credencial de afiliación del IMSS, al ISSSTE o documento migratorio que constate su legal estancia como extranjero en el país.
            </p>
            <p>
                En caso de que, a juicio del Departamento, la información de la Solicitud sea incorrecta, esté incompleta, o falten documentos de acreditación, éste podrá requerirle que proporcione los elementos debidos, dentro de los cinco (5) días hábiles sucesivos a la recepción de la Solicitud. Usted dispondrá de diez (10) días hábiles para atender el requerimiento, contados desde el día siguiente en que haya recibido el mismo. No se considerará presentada la Solicitud correspondiente en caso de que usted no presente respuesta en el plazo establecido.
            </p>
            <p>
                El Departamento le informará sobre la procedencia de su petición así como la disposición a adoptar, en un plazo no mayor a veinte (20) días hábiles contados a partir de la fecha en que la recibió. Si la consideración resulta procedente, se efectuará la resolución dentro de los quince (15) días hábiles desde haberle comunicado la decisión y se le notificará una vez concluida su implementación.
            </p>
            <p>
                En caso de que en la Solicitud marque por objeto limitar la transferencia de sus datos personales a terceros, y de que la consideración de su moción resulte procedente, IGL lo registrará en el listado de exclusión correspondiente.
            </p>
        </div>

        <!-- 9. Departamento de Datos Personales -->
        <div class="privacy-contact-card">
            <h3>Departamento de Datos Personales</h3>
            <div class="privacy-contact-grid">
                <div class="privacy-contact-item">
                    <label>Responsable</label>
                    <span>Departamento de Datos Personales de Inmobiliaria Granjas de la Loma, SAPI de CV</span>
                </div>
                <div class="privacy-contact-item">
                    <label>Domicilio</label>
                    <span>Blvd. Juan Alonso de Torres # 2002 int. 3001, Col. Valle del Campestre, C.P. 37125, León, Guanajuato.</span>
                </div>
                <div class="privacy-contact-item">
                    <label>Correo Electrónico</label>
                    <a href="mailto:datospersonales@grupoalcon.com">datospersonales@grupoalcon.com</a>
                </div>
                <div class="privacy-contact-item">
                    <label>Horario de Atención</label>
                    <span>Lunes a jueves de 9:00 a 14:00 y 16:00 a 19:00 hrs.<br>Viernes de 8:00 a 15:30 hrs.</span>
                </div>
            </div>
        </div>

        <!-- 10. Modificaciones -->
        <div class="privacy-section" style="margin-top: 2rem;">
            <h2>Modificaciones al Aviso de Privacidad</h2>
            <p>
                <strong>Grupo AlCon</strong> se reserva el derecho de efectuar en cualquier momento modificaciones al presente Aviso de Privacidad, para la atención de novedades legislativas, políticas internas o nuevos requerimientos para la prestación u ofrecimiento de nuestros servicios o productos. En tal caso, publicará dentro del sitio web la actualización del referido documento e indicará la fecha de la versión más reciente.
            </p>
            <p>
                En la medida que usted no solicite, en los términos antes mencionados, la cancelación y/u oposición de sus datos personales y continúe accediendo y/o utilizando, parcial o totalmente, nuestros servicios, implicará que ha aceptado y consentido tales cambios y/o modificaciones.
            </p>
            <p>
                Si usted considera que su derecho de protección de datos personales ha sido lesionado por alguna conducta de nuestros empleados, de nuestras actuaciones o respuestas, o presume que en el tratamiento de sus datos personales existe alguna violación a las disposiciones previstas en la LFPDPPP, podrá interponer la queja o denuncia correspondiente ante el Instituto Nacional de Transparencia, Acceso a la Información y Protección de Datos Personales (INAI / antes IFAI), para mayor información visite: <a href="https://home.inai.org.mx/" target="_blank" rel="noopener noreferrer" style="color: var(--color-accent); text-decoration: underline;">home.inai.org.mx</a>.
            </p>
        </div>
    </div>
</div>

<?php
get_footer();
