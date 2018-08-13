<template>
    <div class="students">
        <div class="container">
            <br><br>
            <div class="row">
                <div class="col-md-4">
                    <img src="img/logos/logo-universidad-construrama.png" class="img-responsive" alt="">
                </div>
                <div class="col-md-8">
                    <p>Formato de Pre-registro para participar en la Universidad Construrama</p>
                    <p>Información o dudas favor de contactar a <a href="mailto:uconstrurama@cemex.com">uconstrurama@cemex.com</a></p>
                </div>
            </div>
            <br><br><br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" :class="{'has-error': student.error.name}">
                        <label><span class="text-danger">*</span> Nombre (s)</label>
                        <input type="text" class="form-control"
                            v-model="student.data.name">
                        <span class="help-block" v-if="student.error.name">{{student.error.name[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.last_name}">
                        <label><span class="text-danger">*</span> Apellido (s)</label>
                        <input type="text" class="form-control"
                            v-model="student.data.last_name">
                        <span class="help-block" v-if="student.error.last_name">{{student.error.last_name[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.gender_id}">
                        <label><span class="text-danger">*</span> Sexo</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.gender_id">
                            <option v-for="option in lists.genders"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.gender_id">{{student.error.gender_id[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.birthday}">
                        <label><span class="text-danger">*</span> Fecha de nacimiento</label>
                        <input type="date" class="form-control"
                            v-model="student.data.birthday">
                        <span class="help-block" v-if="student.error.birthday">{{student.error.birthday[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.study_id}">
                        <label><span class="text-danger">*</span> Nivel de Estudio Actual</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.study_id">
                            <option v-for="option in lists.studies"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.study_id">{{student.error.study_id[0]}}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" :class="{'has-error': student.error.position_id}">
                        <label><span class="text-danger">*</span> Puesto Actual</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.position_id">
                            <option v-for="option in lists.positions"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.position_id">{{student.error.position_id[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.years_in_position}">
                        <label><span class="text-danger">*</span> Años de antiguedad en el puesto</label>
                        <input type="number" class="form-control"
                            v-model="student.data.years_in_position">
                        <span class="help-block" v-if="student.error.years_in_position">{{student.error.years_in_position[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.payment_method_id}">
                        <label><span class="text-danger">*</span> Forma de pago</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.payment_method_id">
                            <option v-for="option in lists.paymentMethods"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.payment_method_id">{{student.error.payment_method_id[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.certificates}">
                        <label><span class="text-danger">*</span> Diplomados a los que Aplicas</label>
                        <div class="checkbox" v-for="(option, index) in lists.certificates" :key="index">
                            <label>
                                <input type="checkbox" :id="option.name"
                                :value="option"
                                v-model="student.data.certificates"> {{option.name}}
                            </label>
                        </div>
                        <span class="help-block" v-if="student.error.certificates">{{student.error.certificates[0]}}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" v-model="student.data.have_studied"> ¿Has cursado algún diplomado en la Universidad Construrama?
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" v-show="student.data.have_studied">
                        <label>¿Cual fue tu nombre de usuario?</label>
                        <input type="text" class="form-control"
                            v-model="student.data.username">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" v-show="student.data.have_studied">
                        <label>¿En que Mes y año cursaste?</label>
                        <input type="date" class="form-control"
                            v-model="student.data.year_month">
                    </div>
                </div>
            </div>
            <br><hr>
            <div class="row">
                <div class="col-md-12">
                    <h4>Información de contacto</h4>
                    <br>
                </div>
                <div class="col-md-6">
                    <div class="form-group" :class="{'has-error': student.error.cellphone}">
                        <label><span class="text-danger">*</span> Teléfono Celular</label>
                        <the-mask class="form-control" v-model="student.data.cellphone" :mask="['(###) #######']" />
                        <span class="help-block" v-if="student.error.cellphone">{{student.error.cellphone[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.phone}">
                        <label><span class="text-danger">*</span> Teléfono Fijo</label>
                        <the-mask class="form-control" v-model="student.data.phone" :mask="['(###) #######']" />
                        <span class="help-block" v-if="student.error.phone">{{student.error.phone[0]}}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" :class="{'has-error': student.error.email}">
                        <label><span class="text-danger">*</span> Correo electrónico</label>
                        <input type="email" class="form-control"
                            v-model="student.data.email">
                        <span class="help-block" v-if="student.error.email">{{student.error.email[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.email_confirmation}">
                        <label><span class="text-danger">*</span> Confirma correo electrónico</label>
                        <input type="email" class="form-control"
                            v-model="student.data.email_confirmation">
                        <span class="help-block" v-if="student.error.email_confirmation">{{student.error.email_confirmation[0]}}</span>
                    </div>
                </div>
            </div>
            <br><hr>
            <div class="row">
                <div class="col-md-12">
                    <h4>Información Construrama</h4>
                    <p>Si no encuentras tu tienda Construrama contactar a <a href="mailto:uconstrurama@cemex.com">uconstrurama@cemex.com</a></p>
                    <br>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.state_id}">
                        <label><span class="text-danger">*</span> Estado</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.state_id">
                            <option v-for="option in lists.states"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.state_id">{{student.error.state_id[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.city_id}">
                        <label><span class="text-danger">*</span> Ciudad</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.city_id">
                            <option v-for="option in this.lists.cities"
                                :value="option.id">
                                {{ option.name }} - {{ option.leader_name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.city_id">{{student.error.city_id[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.store_id}">
                        <label><span class="text-danger">*</span> Construrama ({{this.lists.stores.length}})</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.store_id">
                            <option v-for="option in lists.stores"
                                :value="option.id">
                                {{ option.name }} - {{option.street}}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.store_id">{{student.error.store_id[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.store_phone}">
                        <label><span class="text-danger">*</span> Teléfono</label>
                        <the-mask class="form-control" v-model="student.data.store_phone" :mask="['(###) #######']" />
                        <span class="help-block" v-if="student.error.store_phone">{{student.error.store_phone[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.store_email}">
                        <label><span class="text-danger">*</span> Correo electrónico</label>
                        <input type="email" class="form-control"
                            v-model="student.data.store_email">
                        <span class="help-block" v-if="student.error.store_email">{{student.error.store_email[0]}}</span>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-6">
                    <a href="#" data-toggle="modal" data-target="#myModal">Aviso de Privacidad</a>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-success"
                            @click="registerStudent">Guardar</button>
                </div>
            </div>
            <br><br><br>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <p class="text-center"><strong>Aviso de Privacidad</strong></p>
                        <h6 class="text-center">UNIVERSIDAD CONSTRURAMA </h6>
                        <p style="white-space: pre-wrap;">
La Universidad Construrama, (en lo sucesivo, el "Responsable"), con domicilio en la Avenida Constitución 444 poniente, en la Colonia Centro, en Monterrey, Nuevo León, C.P. 64000, hace de su conocimiento que los datos personales y/o datos sensibles que usted libre y voluntariamente llegare a proporcionarle y al respecto le informa lo siguiente:

1. DISPOSICIONES GENERALES
La Ley Federal de Protección de Datos Personales en Posesión de Particulares, otorga derechos de Acceso, Rectificación, Cancelación y/o Oposición (ARCO), respecto del contenido o forma de manejo de los datos que usted pone en conocimiento del "Responsable", para que sus datos personales sean debidamente protegidos durante su tratamiento.
A efecto de cumplir con lo que dispone la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, el "Responsable", guarda la confidencialidad de la información proporcionada por parte de sus alumnos solicitados y obtenidos con un fin legítimo durante las gestiones que diariamente realiza.
El presente texto tiene como objeto dar a conocer a alumnos las características esenciales del tratamiento que se les da a sus datos personales para que los Participantes tengan pleno control y decisión sobre éstos. Por ello, le recomendamos que lea atentamente la siguiente información. Asimismo, este texto regula el tratamiento de los datos personales que se obtienen de los alumnos.
El Responsable hace de su conocimiento que los datos personales proporcionados por usted, se  podrán recabar personalmente de forma verbal o escrita, vía telefónica, por correo electrónico y/o cualquier otro medio electrónico.
2. IDENTIDAD Y DOMICILIO DE LOS RESPONSABLES
La Universidad Construrama, (en lo sucesivo, el "Responsable"), cuenta con domicilio en la Avenida Constitución 444 poniente, en la Colonia Centro, en Monterrey, Nuevo León, C.P. 64000, y a efecto de cumplir con lo que dispone la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, guarda la confidencialidad de la información proporcionada por parte de sus alumnos solicitados y obtenidos con un fin legítimo durante las gestiones que diariamente realiza.

3. DATOS PERSONALES - FINALIDADES
Para poder ser alumno de la Universidad Construrama, se requiere que el aspirante proporcione ciertos datos personales. Sus datos personales serán tratados para realizar todas las actividades primarias y necesarias para los servicios educativos que se le proporcionarán; asimismo, para cumplir con las finalidades anteriormente descritas no se recabarán ni tratarán datos personales sensibles.

Los datos proporcionados serán utilizados para lo siguiente:

- Validar su información y documentación.

-Inscribirlo a la Universidad Construrama, darlo de alta en nuestros sistemas, integrar su expediente académico, y expedirle su credencial de alumno.

-Darle información y proporcionarle los servicios educativos que ofrece la Universidad Construrama, de acuerdo con el programa académico al que se inscribió, así como contactarlo con relación a los trámites administrativos correspondientes a sus estudios.

-Llevar control de acceso vehicular.

-Invitarlo y, en su caso, registrarlo para que participe en actividades extracurriculares con el objeto de contribuir a su formación integral.

-Contactarlo con relación a las actividades académicas, culturales, deportivas o religiosas en las que participe, haya participado, o esté interesado en participar.

-Prestarle material para actividades académicas y extracurriculares.

-Recibirle pagos, emitirle comprobantes de pago, estados de cuenta, así como documentar y realizar gestiones de cobranza en caso de adeudo. Lo anterior también podrá realizarse con aquellas personas que se hagan cargo de cubrir los costos de los servicios que le brindamos a Usted.

-Brindarle apoyo académico con herramientas tecnológicas.

-Crear directorio de alumnos.

-Conocer su perfil para elaborar estrategias didácticas, brindarle asesorías y llevar control de las mismas.

-Brindarle apoyo psicopedagógico.

-Darle seguimiento a su avance académico.

-Gestionar solicitudes de beca o financiamiento.

-Realizar gestiones administrativas y de servicios escolares.

-Practicarle, registrar y revisar sus evaluaciones.

-Almacenar y elaborar controles de sus calificaciones e historial académico.

-Elaborar y archivar su historial médico para prevenir contingencias y brindarle apoyo en caso de que tenga necesidades especiales de salud, así como brindarle atención médica.

-Realización de sus prácticas profesionales.

-Efectuar trámites administrativos relacionados con sus estudios, equivalencia y revalidación de estudios, servicio social, titulación y expedición de cédulas, internamente y ante las autoridades educativas.

-Enviarle comunicaciones internas sobre acontecimientos de la vida universitaria.

-Realizar trámites y gestiones relacionados con seguros.

-Conocer y atender sus dudas, comentarios y sugerencias en relación con las actividades curriculares y extracurriculares ofrecidas por la Universidad Construrama.

-Realizarle un estudio socioeconómico para efectos de beca o financiamiento.

-Apoyarlo para la obtención de alojamiento. Invitarlo y, en su caso, inscribirlo a programas de intercambio académico, así como darle el seguimiento escolar y administrativo a su participación en dichos programas.

-Video-vigilancia, por razones de seguridad.

-Cumplir con la normatividad aplicable y requerimientos de las autoridades con base en una ley.

-Contactar en caso de emergencia a las personas indicadas como referencia.

-Enviarle felicitaciones por su cumpleaños o logros conseguidos.

-Enviarle información promocional de nuestra oferta académica y cultural.

-Divulgar su imagen y nombre, así como en su caso, datos académicos y laborales para efectos promocionales y estadísticos de la Universidad Construrama.

-Solicitar su colaboración para la elaboración de rankings, acreditaciones, y temas promocionales de la Universidad Construrama
.
-Apoyarlo para obtener o cambiar de empleo.

-Realizar encuestas y obtener su retroalimentación para mejorar nuestros servicios.

-Adicionalmente, su información personal será utilizada con las siguientes finalidades secundarias:


Para llevar a cabo las finalidades descritas en el presente aviso de privacidad, utilizaremos las siguientes categorías de datos personales: Le informamos que integraremos a su expediente académico los datos personales recabados de usted durante el proceso de admisión.

En virtud de lo anterior, sus datos personales referidos o entregados por cualquier motivo, a través de cualquier medio, por usted, sólo se utilizarán para fines antes señalados.

Sus datos serán tratados de conformidad con los principios de licitud, consentimiento, información, calidad, finalidad, lealtad, proporcionalidad y responsabilidad en términos de lo dispuesto en la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.
Por tanto si usted no se opone, el Responsable tratará sus datos personales para realizar todas las actividades primarias y necesarias para proporcionarle los servicios educativos que se le proporcionarán.
Sin embargo, se le hace de su conocimiento que si después de no haberse opuesto al tratamiento de datos personales y es su deseo se dejen de tratar dichos datos, puede hacer valer este derecho  través del correo electrónico uconstrurama@cemex.com y/o al teléfono 01 800 122 212.

4. USO DE COOKIES
Que son los cookies y web beacons
Las cookies son archivos de texto que son descargados automáticamente y almacenados en el disco duro del equipo de cómputo del usuario al navegar en una página de internet específica, que permiten recordar al servidor del internet algunos de sus datos, entre ellos sus preferencias para la visualización de las páginas en el servidor, nombre y contraseña.
Por su parte, las web beacons son imágenes insertadas en una página de internet o correo electrónico, que pueden ser utilizadas para monitorear el comportamiento de un visitante, como almacenar información sobre la dirección IP del usuario, duración del tiempo de interacción en dicha página y el tipo de navegador utilizado, entre otros.
5. CAMBIOS AL AVISO PRIVACIDAD
El Responsable se reserva el derecho a periódicamente actualizar este aviso de privacidad. Si se cambia este aviso de privacidad de alguna manera, se publicará un aviso informando sobre el cambio en la página web www.uconstrurama.com. Es responsabilidad del Alumno revisar el contenido actualizado del aviso de privacidad disponible en la página de Internet.  Asumiendo que si el Participante del Programa no se opone a los cambios, significa que el Participante del Programa ha leído, entendido y consentido los términos ahí establecidos. Recomendamos que visite, de tiempo en tiempo, la página de Internet del Responsable para saber de cualquier modificación a este aviso de privacidad.

6. PROCEDIMIENTO PARA EJERCER LOS DERECHOS ARCO
En cualquier momento el titular podrá hacer ejercicio de los derechos de acceso, rectificación, cancelación, oposición, limitación de uso o divulgación de los datos o revocación del consentimiento otorgado, a fin de que no se haga uso de los mismos. Para ello, el titular podrá ejercer dichos derechos ante el  RESPONSABLE a través de la página web, www.uconstrurama.com y/o al teléfono 01 800 122 212; además, se hace del conocimiento del titular de los datos, que la Ley Federal de Protección de Datos Personales en Posesión de los Particulares prevé derechos a su favor para solicitar la protección de sus datos personales

En los términos permitidos por la normatividad aplicable, es su derecho conocer qué datos personales tenemos de usted, para qué los utilizamos y las condiciones del uso que les damos (Acceso). Asimismo, es su derecho solicitar la corrección de su información personal en caso de que esté desactualizada, sea inexacta o incompleta (Rectificación); que la eliminemos de nuestros registros o bases de datos cuando considere que la misma no está siendo utilizada conforme a las disposiciones previstas en la normatividad aplicable (Cancelación);; y como oponerse al uso de sus datos personales para fines específicos (Oposición). Estos derechos se conocen como derechos ARCO. Asimismo, usted tiene el derecho de revocar en cualquier momento el consentimiento que en su caso nos haya otorgado para el tratamiento de sus datos personales, salvo que no sea procedente en términos de la normatividad aplicable, lo cual haríamos de su conocimiento en nuestra respuesta a su solicitud de revocación.

Para conocer el procedimiento, requisitos y plazos para el ejercicio de los derechos ARCO, así como del derecho de revocación, le agradeceremos se ponga en contacto con el "Responsable", a través de la página web, www.uconstrurama.com, o a la mencionada dirección de correo electrónico uconstrurama@cemex.com y/o al teléfono 01 800 122 212. Si desea ejercer alguno o algunos de estos derechos, deberá presentar la solicitud respectiva al  nuestro "Responsable", vía correo electrónico, quien dará trámite a la misma y atenderá cualquier comentario, duda o queja que pudiera tener respecto al tratamiento de su información personal.


El "Responsable", velará en todo momento por la protección de su información personal en cumplimiento con la normatividad aplicable.

Asimismo, le informamos que el Instituto Nacional de Transparencia, Acceso a la Información y Protección de Datos Personales, es la autoridad encargada de vigilar por la debida observancia de las disposiciones legales en materia de protección de datos personales.

7.- Formas en que puede limitar el uso o divulgación de la información personal

Adicionalmente al procedimiento contemplado en la sección inmediata anterior, usted puede limitar el uso o divulgación de su información personal, enviando un correo electrónico a nuestro enlace de información, al correo electrónico uconstrurama@cemex.com, solicitando su inscripción al “Listado de Exclusión Publicitaria”, con el objeto de asegurarse que su información no será utilizada para fines mercadotécnicos o publicitarios.

8.- Transferencia de sus datos personales

Sus datos personales podrán ser transferidos a cualquiera de las empresas filiales de la Universidad Construrama, en cuyos casos no requerimos nos otorgue consentimiento:

Usted puede manifestar su negativa para las transferencias de las que requerimos su consentimiento, enviando un correo electrónico a nuestro enlace de información, al correo electrónico uconstrurama@cemex.com.

Si no lo hace, entenderemos que nos ha otorgado su consentimiento.

9.- Medidas de seguridad para proteger su información personal

Su información personal será resguardada bajo estricta confidencialidad, y para prevenir razonablemente el uso o divulgación indebida de la misma, hemos implementado medidas de seguridad físicas, técnicas y administrativas de conformidad con la normatividad aplicable. En particular, contamos con un enlace de información, una política de privacidad, cursos de capacitación a nuestros empleados, acceso restringido a información personal sólo a usuarios autorizados, un inventario de datos personales (debidamente clasificados por categoría de datos) y de los sistemas de tratamiento, análisis de riesgo y cláusulas contractuales.

10.- Cambios al aviso de privacidad

La Universidad Construrama, podrá modificar y/o actualizar el presente aviso de privacidad, informándole sobre cualquier modificación o actualización a través de nuestra página de Internet www.uconstrurama.com,


11.- Consentimiento

Por este medio otorgo mi consentimiento para que mis datos personales sean tratados de conformidad con los términos del presente aviso de privacidad.
ID: Nombre completo del alumno(a) Firma
Nombre completo del padre o tutor Firma
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import swal from 'sweetalert'
import TheMask from 'vue-the-mask'
export default {
    data() {
        return {
            lists: {
                certificates: [],
                genders: [],
                paymentMethods: [],
                positions: [],
                studies: [],
                states: [],
                cities: [],
                stores: []
            },
            student: {
                data: {
                    certificates: []
                },
                error: {}
            }
        }
    },
    mounted () {
        this.getLists()
    },
    watch: {
        'student.data.state_id' (val) {
            if (val) {
                axios.get('/api/students/lists/cities/' + val)
                .then(response => {
                    this.lists.cities = response.data
                })
            }
        },
        'student.data.city_id' (val) {
            if (val) {
                axios.get('/api/students/lists/stores/' + val)
                .then(response => {
                    this.lists.stores = response.data
                })
            }
        }
    },
    methods: {
        getLists () {
            axios.get('/api/students/lists/certificates')
            .then(response => {
                this.lists.certificates = response.data
            })
            axios.get('/api/students/lists/genders')
            .then(response => {
                this.lists.genders = response.data
            })
            axios.get('/api/students/lists/paymentMethods')
            .then(response => {
                this.lists.paymentMethods = response.data
            })
            axios.get('/api/students/lists/positions')
            .then(response => {
                this.lists.positions = response.data
            })
            axios.get('/api/students/lists/studies')
            .then(response => {
                this.lists.studies = response.data
            })
            axios.get('/api/students/lists/states')
            .then(response => {
                this.lists.states = response.data
            })
        },
        registerStudent (e) {
            var btn = $(e.target).button('loading')

            if (!this.student.data.have_studied)
                this.student.data.have_studied = false;

            axios.post('/api/students/register', this.student.data)
            .then(response => {
                this.student = {
                    data: {},
                    error: {}
                }

                var date = null;
                if (moment().isBefore('2018-04-28')) {
                    date = '7 de Mayo de 2018'
                } else {
                    date = '4 de Junio de 2018'
                }

                swal({
                    title: 'Registro Exitoso',
                    text: `Validaremos tu información<br>Arranque de cursos ${date}<br>Cualquier duda contáctanos en <a href="mailto:uconstrurama@cemex.com">uconstrurama@cemex.com</a>`,
                    html: true,
                    type: 'success'
                })
                btn.button('reset')
            })
            .catch(error => {
                if (error.response.status == 400) {
                    swal({
                        title: 'Registro Fallido',
                        text: `El correo electronico ya fue registrado anteriormente<br>Si tienes dudas contáctanos en <a href="mailto:uconstrurama@cemex.com">uconstrurama@cemex.com</a>`,
                        html: true,
                        type: 'error'
                    })
                } else if (error.response.status != 422) {
                    swal({
                        title: 'Registro Fallido',
                        text: `Ocurrio un problema al momento de registrarte<br>Intentalo de nuevo, si el problema persiste contáctanos en <a href="mailto:uconstrurama@cemex.com">uconstrurama@cemex.com</a>`,
                        html: true,
                        type: 'error'
                    })
                }
                this.student.error = error.response.data;
                btn.button('reset')
            });
        }
    }
}
</script>
