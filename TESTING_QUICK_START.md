# 🧪 TESTING RÁPIDO - Sistema de Evidencias y Firmas

## ⚡ 3 Pasos para Probar en 5 Minutos

### Paso 1: Crear Tarea de Prueba

```bash
php artisan sisgedi:test-task
```

Verás:
```
✓ Tarea creada exitosamente
  ID: 8
  Colaborador: Carlos Testero (ID: 5)
  Instructor: Dr. Juan López García (ID: 1)
```

### Paso 2: Colaborador Sube Evidencia

1. Abre: `http://127.0.0.1:8000/sisgedi`
2. Login: ID = **5**
3. Click: **Mis Tareas**
4. Busca: **"Prueba: Informe"**
5. Click: **"+ Subir Evidencia"**
6. Selecciona un archivo
7. Click: **"Subir Evidencia"** ✓

### Paso 3: Instructor Aprueba y Firma

1. Logout y Login: ID = **1**
2. Click: **Revisión de Entregables**
3. Busca: **"Prueba: Informe"**
4. Click: **"Firmar y Aprobar"**
5. Confirma: **"Aceptar"** ✓

---

## ✅ Resultado Esperado

- ✓ Colaborador vio su tarea
- ✓ Colaborador subió archivo exitosamente
- ✓ Instructor aprobó y firmó
- ✓ Firma mostró: "1 docs firmados"

---

## 📚 Documentación Detallada

- Guía paso a paso: `Modules/SISGEDI/TESTING_GUIDE.md`
- Referencia rápida: `Modules/SISGEDI/TESTING_README.md`

---

¡Listo! 🎉
