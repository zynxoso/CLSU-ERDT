<div class="current-semester-chip inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200 shadow-sm hover:bg-green-200 transition-all duration-200 relative"
     x-data="{ showTooltip: false }"
     @mouseenter="showTooltip = true"
     @mouseleave="showTooltip = false"
     @focus="showTooltip = true"
     @blur="showTooltip = false"
     tabindex="0"
     role="button"
     aria-label="{{ $this->getTooltipText() }}">
    
    <!-- Semester Text -->
    <span class="font-semibold">{{ $this->semesterLabel }}</span>
    
    <!-- Mobile Responsive Styles -->
    <style>
        @media (max-width:640px){.current-semester-chip{font-size:0.75rem;padding:0.375rem 0.75rem;}.current-semester-chip span{max-width:150px;overflow:hidden;text-overflow:ellipsis;}}
        @media (max-width:480px){.current-semester-chip [x-show="showTooltip"]{left:0 !important;transform:none !important;max-width:250px;white-space:normal;word-wrap:break-word;}}
        .current-semester-chip{background-color:rgb(220 252 231) !important;color:rgb(22 101 52) !important;border-color:rgb(187 247 208) !important;}
        .current-semester-chip:hover{background-color:rgb(187 247 208) !important;}
        .current-semester-chip:focus{outline:2px solid rgb(34 197 94);outline-offset:2px;}
    </style>

</div>