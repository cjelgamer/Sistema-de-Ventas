<template>
    <div :class="[containerClass, 'flex items-center justify-center']">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 120" class="w-full h-full">
        <!-- Doble borde del óvalo -->
        <ellipse 
          cx="200" 
          cy="60" 
          rx="190" 
          ry="55" 
          fill="#0047AB"
        />
        <ellipse 
          cx="200" 
          cy="60" 
          rx="185" 
          ry="50" 
          fill="none" 
          stroke="white" 
          stroke-width="2"
        />
        <ellipse 
          cx="200" 
          cy="60" 
          rx="180" 
          ry="45" 
          fill="none" 
          stroke="white" 
          stroke-width="2"
        />
        
        <!-- Texto principal con estilo cursiva -->
        <text 
          x="200" 
          y="55" 
          font-family="'Arial', sans-serif" 
          font-weight="bold" 
          font-size="42" 
          fill="white" 
          text-anchor="middle" 
          dominant-baseline="middle"
          style="font-style: italic; letter-spacing: 1px;"
        >
          ANDREATEX
        </text>
        
        <!-- G&K text -->
        <text 
          x="200" 
          y="85" 
          font-family="'Arial', sans-serif" 
          font-weight="bold" 
          font-size="32" 
          fill="white" 
          text-anchor="middle" 
          dominant-baseline="middle"
          style="letter-spacing: 2px;"
        >
          G&amp;K
        </text>
      </svg>
    </div>
  </template>
  
  <script>
  export default {
    name: 'LogoAndreatex',
    props: {
      containerClass: {
        type: String,
        default: 'w-28 h-28' // Tamaño por defecto
      }
    }
  }
  </script>