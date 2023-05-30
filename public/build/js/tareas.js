!function(){o();let e=[],t=[];const a=window.location.origin;document.querySelector("#agregar-tarea").addEventListener("click",(function(){i()}));function n(a){const n=a.target.value;t=""!==n?e.filter(e=>e.estado===n):[],r()}async function o(){try{const t="/api/tareas?id="+d(),a=await fetch(t),n=await a.json();e=n.tareas,r()}catch(e){console.log(e)}}function r(){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),function(){const t=e.filter(e=>"0"===e.estado),a=document.querySelector("#pendientes");0===t.length?a.disabled=!0:a.disabled=!1}(),function(){const t=e.filter(e=>"1"===e.estado),a=document.querySelector("#completadas");0===t.length?a.disabled=!0:a.disabled=!1}();const n=t.length?t:e;if(0===n.length){const e=document.querySelector("#listado-tareas"),t=document.createElement("LI");return t.textContent="No Hay Tarea",t.classList.add("no-tareas"),void e.appendChild(t)}const r={0:"Pendiente",1:"Completa"};n.forEach(t=>{const n=document.createElement("LI");n.dataset.tareaId=t.id,n.classList.add("tarea");const c=document.createElement("P");c.textContent=t.nombre,c.onclick=function(){i(editar=!0,{...t})};const l=document.createElement("DIV");l.classList.add("opciones");const u=document.createElement("BUTTON");u.classList.add("estado-tarea"),u.classList.add(""+r[t.estado].toLowerCase()),u.textContent=r[t.estado],u.dataset.estadoTarea=t.estado,u.onclick=function(){!function(e){const t="1"===e.estado?"0":"1";e.estado=t,s(e)}({...t})};const m=document.createElement("BUTTON");m.classList.add("eliminar-tarea"),m.textContent="Eliminar",m.dataset.idTarea=t.id,m.onclick=function(){!function(t){Swal.fire({title:"¿Eliminar Tarea?",showDenyButton:!0,confirmButtonText:"Si",cancelButtonText:"No"}).then(n=>{n.isConfirmed&&n.isConfirmed&&async function(t){const{id:n,nombre:r,estado:i}=t,c=new FormData;c.append("id",n),c.append("nombre",r),c.append("estado",i),c.append("proyectoId",d());try{const n=a+"/api/tarea/eliminar",r=await fetch(n,{method:"POST",body:c}),i=await r.json();"exito"===i.tipo?Swal.fire({icon:"success",title:"Eliminado!",text:i.mensaje}):Swal.fire({icon:"error",title:"No se pudo Eliminar!",text:i.mensaje}),e=e.filter(e=>e!==t.id),o()}catch(e){console.log(e)}}(t)})}({...t})},l.appendChild(u),l.appendChild(m),n.appendChild(c),n.appendChild(l);document.querySelector("#listado-tareas").appendChild(n)})}function i(t=!1,n={}){const o=document.createElement("DIV");o.classList.add("modal"),o.innerHTML=`\n        <form class="formulario nueva-tarea">\n            <legend>${t?"Editar Tarea":"Añade una nueva Tarea"}</legend>\n            <div class="campo">\n                <label>Tarea</label>\n                <input type="text"\n                placeholder="${n.nombre?"Edita la Tarea":"Añadir Tarea al Proyecto Actual"}";\n                name="tarea"\n                id="tarea"\n                value="${t?n.nombre:""}">\n            </div>\n            <div class="opciones">\n                <input type="submit" value="${t?"Actuaizar Tarea":"Añadir Tarea"}" class="submit-nueva-tarea">\n                <button type="button" class="cerrar-modal">Cancelar</button>\n            </div>\n        </form>\n        `,document.querySelector(".dashboard").appendChild(o);const i=document.querySelector(".formulario");setTimeout(()=>{i.classList.add("animar")},0),o.addEventListener("click",(function(i){if(i.preventDefault(),i.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{o.remove()},500)}if(i.target.classList.contains("submit-nueva-tarea")){const o=document.querySelector("#tarea").value.trim();if(""===o)return void c("El nombre de la Tarea es obligatorio","error",document.querySelector(".formulario legend "));t?(n.nombre=o,s(n)):async function(t){const n=new FormData;n.append("nombre",t),n.append("proyectoId",d());try{const o=a+"/api/tarea",i=await fetch(o,{method:"POST",body:n}),s=await i.json();if(c(s.mensaje,s.tipo,document.querySelector(".formulario legend ")),"exito"===s.tipo){const a=document.querySelector(".modal");setTimeout(()=>{a.remove()},3e3);const n={id:String(s.id),nombre:t,estado:"0",proyectoId:s.proyectoId};e=[...e,n],r()}}catch(e){console.log(e)}}(o)}}))}function c(e,t,a){document.querySelector(".alerta")&&n.remove();const n=document.createElement("DIV");n.classList.add("alerta",t),n.textContent=e,a.parentElement.insertBefore(n,a.nextElementSibling),setTimeout(()=>{n.remove()},3e3)}async function s(t){const{id:n,nombre:o,estado:i,proyectoId:c}=t,s=new FormData;s.append("id",n),s.append("nombre",o),s.append("estado",i),s.append("proyectoId",d());try{const t=a+"/api/tarea/actualizar",c=await fetch(t,{method:"POST",body:s}),d=await c.json();"exito"===d.respuesta.tipo?Swal.fire({icon:"success",title:d.respuesta.mensaje,text:d.respuesta.mensaje}):Swal.fire({icon:"error",title:d.respuesta.mensaje,text:d.respuesta.mensaje});const l=document.querySelector(".modal");l&&l.remove(),e=e.map(e=>(e.id===n&&(e.nombre=o,e.estado=i),e)),r()}catch(e){console.log(e)}}function d(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).id}document.querySelectorAll('#filtros input[type="radio"]').forEach(e=>e.addEventListener("input",n))}();