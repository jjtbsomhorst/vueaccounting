const formatters = {
    cache: new Map(),
    currency: function(n) {
        let number = parseFloat(n);
        return new Intl.NumberFormat("nl-NL", {
            style: "currency",
            currency: "EUR"
        }).format(number);
    },
    get: function(filternames){
        if(!Array.isArray(filternames)){
            filternames = [filternames];
        }
        
        let key = filternames.join("::");
        if(!this.cache.has(key)){
            let returnValue = {};
            filternames.forEach((entry)=>{
                returnValue[entry] = this[entry];
            });
            this.cache.set(key,returnValue);
        }
        return this.cache.get(key);
        
    }
}
export default formatters;

