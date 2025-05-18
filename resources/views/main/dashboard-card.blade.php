<!-- Student ID Card -->
<div class="student-id-card mb-4">
    <div class="id-card-header">
        <div class="college-logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="header-text">
            <h5>Laovieng College</h5>
            <p class="small text-white">Student Profile</p>
            <p class="small text-white">ໂປຮຟາຍນັກສຶກສາ</p>
        </div>
    </div>    <div class="id-card-body">
        <div class="student-photo d-flex justify-content-center align-items-center" style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin: 0 auto 20px auto;">
            @if($student->picture)
                <img src="{{ asset('storage/' . $student->picture) }}" alt="Student Picture" class="img-fluid rounded-circle student-profile-picture" style="width: 100%; height: 100%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            @else
                <div class="rounded-circle d-flex justify-content-center align-items-center bg-light student-profile-picture" style="width: 100%; height: 100%; border: 3px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <i class="bi bi-person-fill" style="font-size: 5rem; color: #adb5bd;"></i>
                </div>
            @endif
        </div>
        
        <div class="student-details">
            <div class="text-center mb-2">
                <h5 class="student-name">{{ $student->name }} {{ $student->sername }}</h5>
                <div class="student-id">{{ $student->id }}</div>
            </div>
            
            <div class="details-list">
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-venus-mars"></i> ເພດ:</span>
                    <span class="detail-value">{{ $student->gender }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-birthday-cake"></i> ເກີດວັນທີ:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="fas fa-flag"></i> ສັນຊາດ:</span>
                    <span class="detail-value">{{ $student->nationality }}</span>
                </div>
            </div>
            
            {{-- <div class="barcode-container">
                <div class="barcode">
                    <i class="fas fa-barcode fa-lg"></i>
                    <span class="barcode-number">{{ $student->id }}</span>
                </div>
            </div> --}}
        </div>
    </div>
    
    {{-- <div class="id-card-footer">
        <div class="contact-info">
            <div><i class="fas fa-phone"></i> {{ $student->tell }}</div>
            <div><i class="fas fa-map-marker-alt"></i> {{ $student->address }}</div>
        </div>
        <div class="expiry-info">
            <div class="valid-until">Valid until: 31/12/2025</div>
        </div>
    </div> --}}
</div>
