// Unwrap Laravel paginated OR plain API response → always returns an array
export const unwrap = (response) => {
    const d = response?.data?.data;
    if (Array.isArray(d)) return d;        // plain list
    if (Array.isArray(d?.data)) return d.data; // paginated
    return [];
};

export const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

export const formatTime = (time) => {
    if (!time) return '';
    return new Date(time).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

export const formatDateTime = (dateTime) => {
    if (!dateTime) return '';
    return new Date(dateTime).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

export const formatPhone = (phone) => {
    if (!phone) return '';
    const cleaned = phone.replace(/\D/g, '');
    if (cleaned.length === 10) {
        return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6)}`;
    } else if (cleaned.length === 11) {
        return `+${cleaned[0]} (${cleaned.slice(1, 4)}) ${cleaned.slice(4, 7)}-${cleaned.slice(7)}`;
    }
    return phone;
};

export const truncate = (text, length = 50) => {
    return text && text.length > length ? text.substring(0, length) + '...' : text;
};

export const capitalizeRole = (role) => {
    return role?.charAt(0).toUpperCase() + role?.slice(1) || '';
};

export const getStatusClass = (status) => {
    const statusClasses = {
        pending: 'bg-yellow-100 text-yellow-800',
        accepted: 'bg-green-100 text-green-800',
        completed: 'bg-blue-100 text-blue-800',
        canceled: 'bg-red-100 text-red-800',
        approved: 'bg-green-100 text-green-800',
        suspended: 'bg-red-100 text-red-800',
        active: 'bg-green-100 text-green-800',
        inactive: 'bg-gray-100 text-gray-800'
    };
    return statusClasses[status] || 'bg-gray-100 text-gray-800';
};
